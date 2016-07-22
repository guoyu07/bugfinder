#!/usr/bin/perl -l
#
use strict;
use warnings;
use File::Basename;
use Data::Dumper;
use threads;
use threads::shared;
use JSON;

our $verbose = 2;

die "use: $0 <library_code_dir> [<library_code_dir2> <...> <library_code_dirN>] <plugins_dir> <workers>" unless @ARGV >= 3;

sub file_get_contents {
  my $file = $_[0];
  open(my $fh, $file) or print STDERR "$!";
  my $buf = "";
  while(<$fh>) {
    $buf .= $_;
  }
  close($fh);
  return $buf;
}
# from matches reads the whole block
sub read_blocks {
  my $rx = $_[0];
  my $rx2 = $_[1];
  my $depth = $_[2];
  my $buf_ref = $_[3];
  my $buf = $$buf_ref;
  $$buf_ref = "";

  my @blocks = ();
  my $code = "";
  my $c = 0;
  my $open_c = 0;
  my $close_c = 0;
  my $ignore_s = 0;
  my $ignore_d = 0;
  my $ignore_t = 1;
  my $escaped = 0;
  my $copy = 0;
  for (my $i = 0; $i < length($buf); $i++) {
    $escaped = 0;
    my $prev_c = $c;
    $c = substr($buf, $i, 1);
    if ($prev_c eq '<' && $c eq '?' && $ignore_s == 0 && $ignore_d == 0) {
      if ($copy == 1) {
        $code = substr($code, 0, length($code) - 1);
      } else {
        $$buf_ref = substr($$buf_ref, 0, length($$buf_ref) - 1);
      }
      $i ++;
      my $tag = substr($buf, $i, 3);
      if ($tag eq 'php') {
        $i += 2;
      }
      next;
    }
    if ($prev_c eq '?' && $c eq '>' && $ignore_s == 0 && $ignore_d == 0) {
      if ($copy == 1) {
        $code = substr($code, 0, length($code) - 1);
      } else {
        $$buf_ref = substr($$buf_ref, 0, length($$buf_ref) - 1);
      }
      while (!($prev_c eq '<' && $c eq '?') && $i < length($buf)) {
        $prev_c = $c;
        $c = substr($buf, $i, 1);
        $i++;
      }
      if ($prev_c eq '<' && $c eq '?') {
        $i -= 3;
      }
      #print substr($buf, $i, 100);
      next;
    }
    if ($prev_c eq '/' && $c eq '*' && $ignore_s == 0 && $ignore_d == 0) {
      # multiline comment begins
      if ($copy == 1) {
        $code = substr($code, 0, length($code) - 1);
      } else {
        $$buf_ref = substr($$buf_ref, 0, length($$buf_ref) - 1);
      }
      while(!($prev_c eq '*' && $c eq '/') && !($i >= length($buf))) {
        $i++;
        $prev_c = $c;
        $c = substr($buf, $i, 1);
      }
      next;
    }
    if ($prev_c eq '/' && $c eq '/' && $ignore_s == 0 && $ignore_d == 0 && $escaped == 0) {
      # single line comment begins
      if ($copy == 1) {
        $code = substr($code, 0, length($code) - 1);
      } else {
        $$buf_ref = substr($$buf_ref, 0, length($$buf_ref) - 1);
      }
      while(!($prev_c eq '?' && $c eq '>') && !($c eq "\n") && !($i >= length($buf))) {
        $i++;
        $prev_c = $c;
        $c = substr($buf, $i, 1);
      }
      next;
    }
    if ($c eq '#' && $ignore_s == 0 && $ignore_d == 0 && $escaped == 0) {
      # sharp single line comment begins
      while($c ne "\n" && $i < length($buf)) {
        $prev_c = $c;
        $i++;
        $c = substr($buf, $i, 1);
      }
    }
    if (substr($buf, $i, length($rx)) eq $rx && $ignore_s == 0 && $ignore_d == 0 && ($open_c - $close_c) == $depth) {
      my $tmp = $i;
      my $tmp2 = $c;
      my $tmp3 = '';
      while ($c ne '{' && $i < length($buf)) {
        $tmp3 .= $c;
        $i++;
        $c = substr($buf, $i, 1);
      }
      $tmp3 .= $c;
      if ($tmp3 =~ m/$rx2/) {
        # print "CLASS FRAG START";
        # print $tmp3;
        # print "CLASS FRAG END";
        $copy = 1;
      }
      $i = $tmp;
      $c = $tmp2;
    }
    if ($prev_c eq '<' && $c eq '<' && substr($buf, $i + 1, 1) eq '<' && $ignore_s == 0 && $ignore_d == 0) {
      if ($copy == 1) {
        $code .= '<<';
      } else {
        $$buf_ref .= '<<';
      }
      $i += 2;
      my $tag_start_i = $i;
      while ($c ne "\n" && $i < length($buf)) {
        $c = substr($buf, $i, 1);
        if ($copy == 1) {
          $code .= $c;
        } else {
          $$buf_ref .= $c;
        }
        $i++;
      }
      my $tag_end_i = $i - 1;
      substr($buf, $tag_start_i, $tag_end_i - $tag_start_i) =~ m/[\'\"]*(\w+)[\'\"]*/;
      my $tag = $1;
      my $tmp_buf = substr($buf, $i, length($buf) - $i);
      my $block_end_i = $i + index($tmp_buf, "\n$tag;");
      for (; $i < $block_end_i; $i++) {
        if ($copy == 1) {
          $code .= substr($buf, $i, 1);
        } else {
          $$buf_ref .= substr($buf, $i, 1);
        }
      }
    }
    if ($c eq '\\') {
      my $e_times = 0;
      while ($c eq '\\' && $i < length($buf)) {
        if ($copy == 1) {
          $code .= $c;
        } else {
          $$buf_ref .= $c;
        }
        $e_times++;
        $i++;
        $c = substr($buf, $i, 1);
      }
      if ($e_times % 2 == 0) {
        $escaped = 0;
      } else {
        $escaped = 1;
      }
    }
    if ($c eq '\'' && $ignore_d == 0 && $escaped == 0) {
      if ($ignore_s == 0) {
        $ignore_s = 1;
      } else {
        $ignore_s = 0;
      }
    }
    if ($c eq '"' && $ignore_s == 0 && $escaped == 0) {
      if ($ignore_d == 0) {
        $ignore_d = 1;
      } else {
        $ignore_d = 0;
      }
    }
    if ($c eq '{' && $ignore_s == 0 && $ignore_d == 0) {
      $open_c ++;
    } elsif ($c eq '}' && $ignore_s == 0 && $ignore_d == 0) {
      $close_c ++;
    }
    if ($copy == 1) {
      $code .= $c;
    } else {
      $$buf_ref .= $c;
    }
    if ($close_c > 0 && $close_c == $open_c) {
      # print "PUSHING BLOCK";
      # print $code;
      # print "END BLOCK";
      $copy = $open_c = $close_c = 0;
      if ($code ne "") {
        push @blocks, $code;
        $code = "";
      }
    }
  }
  return @blocks;
}

# from matches reads the whole block
sub read_code {
  my $buf = $_[0];
  my @blocks = ();
  my $code = "";
  my $c = 0;
  my $open_c = 0;
  my $close_c = 0;
  my $ignore_s = 0;
  my $ignore_d = 0;
  my $ignore_t = 1;
  my $escaped = 0;
  for (my $i = 0; $i < length($buf); $i++) {
    $escaped = 0;
    my $prev_c = $c;
    $c = substr($buf, $i, 1);
    if ($prev_c eq '<' && $c eq '<' && substr($buf, $i + 1, 1) eq '<' && $ignore_s == 0 && $ignore_d == 0) {
      $code .= '<<';
      $i += 2;
      my $tag_start_i = $i;
      while ($c ne "\n" && $i < length($buf)) {
        $c = substr($buf, $i, 1);
        $code .= $c;
        $i++;
      }
      my $tag_end_i = $i - 1;
      substr($buf, $tag_start_i, $tag_end_i - $tag_start_i) =~ m/[\'\"]*(\w+)[\'\"]*/;
      my $tag = $1;
      my $tmp_buf = substr($buf, $i, length($buf) - $i);
      my $block_end_i = $i + index($tmp_buf, "\n$tag;");
      for (; $i < $block_end_i; $i++) {
        $code .= substr($buf, $i, 1);
      }
    }
    if ($c eq '\\') {
      my $e_times = 0;
      while ($c eq '\\' && $i < length($buf)) {
        $code .= $c;
        $e_times++;
        $i++;
        $c = substr($buf, $i, 1);
      }
      if ($e_times % 2 == 0) {
        $escaped = 0;
      } else {
        $escaped = 1;
      }
    }
    if ($c eq '\'' && $ignore_d == 0 && $escaped == 0) {
      if ($ignore_s == 0) {
        $ignore_s = 1;
      } else {
        $ignore_s = 0;
      }
    }
    if ($c eq '"' && $ignore_s == 0 && $escaped == 0) {
      if ($ignore_d == 0) {
        $ignore_d = 1;
      } else {
        $ignore_d = 0;
      }
    }
    if (($c eq '{' || $c eq '}') && $ignore_d == 0 && $ignore_s == 0) {
      $code = "";
      next;
    }
    if ($c eq '(') {
      $open_c++;
    } elsif ($c eq ')') {
      $close_c++;
    }
    if ($c eq ';' && $ignore_d == 0 && $ignore_s == 0 && ($open_c - $close_c) != 0) {
      $code = "";
      next;
    }
    $code .= $c;
    if ($c eq ';' && $ignore_d == 0 && $ignore_s == 0 && ($open_c - $close_c) == 0) {
      # print "PUSHING BLOCK";
      # print $code;
      # print "END BLOCK";
      if ($code ne "" && $code ne ";") {
        push @blocks, $code;
        $code = "";
      }
      $close_c = $open_c = 0;
    }
  }
  return @blocks;
}

sub load_wp_script {
  my $file = $_[0];
  #my $code_ref = $_[1];
  my $buf = &file_get_contents($file);
  my %code_chunk;
  #print $buf;
  #remove simple comments
  #my @s_comments = $buf =~ m/[;\s\}\{](\/\/[^\n]+)|(^\/\/[^\n]+)/gi;
  #foreach my $s_comment (@s_comments) {
  #  next if !defined $s_comment;
  #  $buf =~ s/\Q$s_comment//g;
  #}
  #implemented on read_blocks()
  #remove multiline comments
  #$buf =~ s/(\/\*[\s\S]+?\*\/)//g;
  #implemented on read_blocks()
  #print $buf;die;
  #match all beggining classes for reading each one
  #my @class_blocks = &read_blocks(qr/(class[\s]*\w+?[\s]*\{)|(class[\s]*\w+?[\s]+extends[\s]+\w+?[\s]*\{)/, $buf);
  my @class_blocks = &read_blocks('class ', qr/(^class[\s]*[\w\_]+?[\s]+extends[\s]+[\w\_\\]+?[\s]+implements[\s]+[\w\_\\]+[\s]*\{)|(^class[\s]*[\w\_\\]+?[\s]+extends[\s]+[\w\_\\]+?[\s]*\{)|(^class[\s]*[\w\_\\]+?[\s]+implements[\s]+[\w\_\\]+?[\s]*\{)|(^class[\s]*[\w\_]+?[\s]*\{)/i, 0, \$buf);
  #print $buf; die;
  # print "@class_blocks";
  # die;
  foreach my $class_block (@class_blocks) {
    my ($class_name, $class_code, $parent_class, $interface);
    ($class_name, $class_code) = $class_block =~ m/^class[\s]*([\w\_]+)?[\s]*\{([\s\S]*)\}/gi;
    ($class_name, $parent_class, $class_code) = $class_block =~ m/^class[\s]*([\w+\_]+)?[\s]+extends[\s]+([\w\_\\]+)?[\s]*\{([\s\S]*)\}/gi if (!defined $class_name);
    ($class_name, $interface, $class_code) = $class_block =~ m/^class[\s]*([\w+\_]+)?[\s]+implements[\s]+([\w\_\\]+)?[\s]*\{([\s\S]*)\}/gi if (!defined $class_name);
    ($class_name, $parent_class, $interface, $class_code) = $class_block =~ m/^class[\s]*([\w+\_\\]+)?[\s]+extends[\s]+([\w\_\\]+)?[\s]*implements[\s]+([\w+\_\\]+)?[\s]*\{([\s\S]*)\}/gi if (!defined $class_name);
    if (!defined $class_code) {
      print $file;
      print $class_block;
      die;
    }
    # print "CLASS CODE BEGIN";
    # print $class_code;
    # print "CLASS CODE END";
    #die;
    #my @func_blocks = &read_blocks(qr/(function[\s]+\w+?[\s]*\([^\)]*[\s]*\)[\s]*\{)/, $class_code);
    $class_code = '<?php ' . $class_code;
    my @func_blocks = &read_blocks('function ', qr/(^function[\s]+([\&\w\_]+)[\s]*\(([^{]*)\)[\s]*\{)/i, 0, \$class_code);
    foreach my $func_block (@func_blocks) {
      my ($func_name, $func_args, $func_code) = $func_block =~ m/^function[\s]+([\&\w]+)[\s]*\(([^{]*)[\s]*\)[\s]*\{([\s\S]*)}/gi;
      if (!defined $func_name) {
        print $file;
        print $func_block;
        #print $class_block;
        die;
      }
      $code_chunk{$file}{'classes'}{$class_name}{'methods'}{$func_name}{'args'} = $func_args;
      $code_chunk{$file}{'classes'}{$class_name}{'methods'}{$func_name}{'code'} = $func_code;
    }
    # if ($class_code) {
    #   print $class_code; die;
    # }
    $code_chunk{$file}{'classes'}{$class_name}{'parent'} = $parent_class;
    $code_chunk{$file}{'classes'}{$class_name}{'interface'} = $interface;
  }
  my @func_blocks = &read_blocks('function ', qr/(^function[\s]+([\&\w\_]+)[\s]*\(([^{]*)\)[\s]*\{)/i, 0, \$buf);
  foreach my $func_block (@func_blocks) {
    # print "FUNC BLOCK BEGIN";
    # print $func_block;
    # print "FUNC BLOCK ENDS";
    my ($func_name, $func_args, $func_code) = $func_block =~ m/^function[\s]+([\&\w]+)[\s]*\(([^{]*)[\s]*\)[\s]*\{([\s\S]*)}/gi;
    if (!defined $func_name) {
      print $file;
      print $func_block;
      #print $class_block;
      die;
    }
    $code_chunk{$file}{'functions'}{$func_name}{'args'} = $func_args;
    $code_chunk{$file}{'functions'}{$func_name}{'code'} = $func_code;
  }
  #print $buf;
  $code_chunk{$file}{'main'} = $buf;
  #match all beggining of functions for reading each one
  #@tmp = $buf =~ m/(function[\s]+\w+?[\s]*\([^\)]*[\s]*\)[\s]*\{)/gi;
  #&read_blocks(@tmp);
  #
  #print dump(%code_chunk);
  return %code_chunk;
}

sub read_code_dir {
  my $dir = $_[0];
  my @files = ();
  opendir(my $dh, $dir) || die "Can't opendir $dir $!";
  while (readdir($dh)) {
    if (-f "$dir/$_" && $_ =~ /\.php$/) {
      push @files, "$dir/$_";
    } elsif (-d "$dir/$_" && $_ ne "." && $_ ne "..") {
      my @tmp = &read_code_dir("$dir/$_");
      push @files, @tmp;
    }
  }
  closedir $dh;
  return @files;
}

sub read_code_dirs {
  my $dirs_ref = $_[0];
  my @files = ();
  return @files unless @{$dirs_ref} > 0;
  foreach my $dir (@{$dirs_ref}) {
    next unless $dir;
    if ($verbose > 0) {
      print "Reading directory " . $dir . " structure...";
    }
    push @files, &read_code_dir($dir);
  }
  return @files;
}

sub lookup_file {
  my $re = $_[0];
  my $hash_ref = $_[1];

  foreach my $path (keys %{$hash_ref}) {
    if ($path =~ /$re/) {
      return %{$hash_ref->{$path}};
    }
  }
  return ();
}

my $workers = pop @ARGV;
my $plugins_dir = pop @ARGV;
my @threads = ();
my $out_file = "library.pm";
my %library = ();
if (-f $out_file) {
  goto dont_compose_module;
}
my @files :shared = &read_code_dirs(\@ARGV);
my @code_json :shared = ();
for (my $wid = 1; $wid <= $workers; $wid++) {
  my $th = threads->create(sub {
    my ($mce) = @_;

    if ($verbose > 0) {
      print "Worker " . $wid . " is launched and parsing library code ...";
    }

    my $break = 0;
    while (1) {
      my $file;
      {
        lock(@files);
        if (@files > 0) {
          $file = pop @files;
        } else {
          $break = 1;
        }
      }
      last if $break;
      my %code_chunk = &load_wp_script($file);
      if (%code_chunk) {
        my $code_json = encode_json \%code_chunk;
        %code_chunk = ();
        push @code_json, $code_json;
        $code_json = undef;
      }
    }
  });
  push @threads, $th;
}

$_->join for @threads;
if ($verbose > 0) {
  print "Composing library's final hash ...";
}
foreach my $chunk_json (@code_json) {
  if ($chunk_json) {
    my $hash_ref = decode_json $chunk_json;
    foreach my $file (keys $hash_ref) {
      my $path = $file;
      $path =~ s/\.\.\///g;
      $path =~ s/\/\//\//g;
      $library{$path} = $hash_ref->{$file};
    }
  }
}
@code_json = ();

# save package for later use
my $out_package = "";
my @out_package_dir = split(/\//, $out_file);
foreach my $out_package_dir (@out_package_dir) {
  if ($out_package_dir) {
    $out_package .= $out_package_dir . "::";
  }
}
$out_package =~ s/\.pm\:\:$//;
if ($verbose > 0) {
  print "Saving parsed software structure as package " . $out_package . " into " . $out_file . " ...";
}
open my $fh, ">", $out_file or die "open: $!";
#print $fh "#!/usr/bin/perl -l\n";
#print $fh "package " . $out_package . ";\n";
#print $fh "use strict;\nuse warnings;\nuse Data::Dump qw(dump);\n";
print $fh "my ";
print $fh Dumper(\%library);
print $fh "\%library = %{\$VAR1};";
#print $fh "sub get_library_ref { return \$VAR1; }";
#print $fh "1;";
#print $fh "\"$out_package\"";
#print $fh "; print dump(\%code);";
close $fh;

dont_compose_module:
if (-f $out_file) {
  if ($verbose > 0) {
    print "Library cache detected, using ...";
  }
  eval(&file_get_contents($out_file));
  # print Dumper(\%library);
  # die;
}

my @plugins_dirs :shared = ();
opendir(my $dh, $plugins_dir) || die "Can't opendir $plugins_dir: $!";
while (readdir($dh)) {
  if (-f "$plugins_dir/$_") {
    next;
  } elsif (-d "$plugins_dir/$_" && $_ ne "." && $_ ne "..") {
    push @plugins_dirs, "$plugins_dir/$_";
  }
}
closedir $dh;

@threads = ();
for (my $wid = 1; $wid <= $workers; $wid++) {
  my $th = threads->create(\&thread_worker, $wid);
  #$th->detach;
  push @threads, $th;
}
#print Dumper(\@threads);
$_->join for @threads;

# this function does the following:
# 1. as it's a worker it pops from @plugins_dirs
# 2. with $plugin_dir reads all the plugin directory structure
# 3. for each php file parse its content and create a hash called %code
# 2. read plugin directory structure
# 3. threaded json array composer
# 4. compose final code hash
# 5. for each main code:
#   5.1. remove from code blocks, only block code, not inner code: for, while, do, foreach, if, else, else if, {}.
#   5.2. compose complete script by interpreting require, include, and so.
#   5.3. remove from code blocks, only block code, not inner code: for, while, do, foreach, if, else, else if, {}.
#   5.4. for each composed main code, interprete function calls by pasting code.
#   5.5. remove from code blocks, only block code, not inner code: for, while, do, foreach, if, else, else if, {}.
#  6. for each final composed main code, search bugs.
sub thread_worker {
  my $wid = $_[0];
  if ($verbose > 1) {
    print "Worker " . $wid . " is launched ...";
  }

  my $break = 0;
  while (1) {
    my $plugin_dir;
    {
      lock (@plugins_dirs);
      if (@plugins_dirs > 0) {
        $plugin_dir = pop @plugins_dirs;
      } else {
        $break = 1;
      }
    }
    last if $break;
    next unless $plugin_dir;
    if ($verbose > 1) {
      print "Pop " . $plugin_dir . " from worker " . $wid;
    }
    my @tmp = ($plugin_dir);
    my @plugin_files = &read_code_dirs(\@tmp);
    if ($verbose > 1) {
      print "Plugin " . $plugin_dir . " has " . @plugin_files . " php files, from worker " . $wid;
    }
    #print dump(@plugin_files);
    if ($verbose > 1) {
      print "Composing plugin's code final hash, from worker " . $wid . "...";
    }
    my %code = ();
    foreach my $plugin_file (@plugin_files) {
      my $path = $plugin_file;
      $path =~ s/\.\.\///g;
      $path =~ s/\/\//\//g;
      my %code_chunk = &load_wp_script($path);
      if (%code_chunk) {
        $code{$path} = $code_chunk{$path};
      }
    }
    #print "Num keys \%code: " . keys %code;

    # for each file in %code, work with 'main' code and compose final code by
    # replacing code inclusions and functions/method calls.
    #print "Num keys \%library: " . keys %library;

    foreach my $plugin_file (keys %code) {
      # search for code inclusion and compose
      if ($verbose > 1) {
        print "Interpreting code inclusions in file " . $plugin_file . ", from worker " . $wid . " ...";
      }
      my @code_lines = &read_code($code{$plugin_file}{'main'});
      next unless @code_lines > 0;
      my $took_effect = 1;
      my @include_trace = ();
      while ($took_effect == 1) {
        $took_effect = 0;
        my %includes_mark = ();
        @tmp = ();
        foreach my $code_line (@code_lines) {
          #print "Num. code_lines: " . @code_lines . " wid " . $wid;
          if ($code_line =~ m/^[\s]*(require_once|include_once|require|include)[\s]*[\(]*([^\$]+?)[\)]{0,1}[\s]*;/i) {
            # match 1 is the included file or expression
            my $include_type = $1;
            my $require_line = $2;
            if ($require_line =~ m/([^\"\']+)[\"\'][\s]*$/) {
              # match 1 is the last part of path in include
              my $path = $1;
              # if (substr($path, 0, 1) ne '/') {
              #   $path = '/' . $path;
              # }
              $path =~ s/\.\.\///g;
              $path =~ s/\/\//\//g;
              my @tmp2 = ();
              # print keys %code;
              # print keys %library;
              my %include = &lookup_file(qr/$path$/, \%code);
              # print "KEYS";
              # print keys %code;
              # print "ENDKEYS";
              if (!%include) {
                %include = &lookup_file(qr/$path$/, \%library);
                if (!%include) {
                  print STDERR "WARNING: can't find file path fragment: " . $path . " , while parsing file " . $plugin_file . ", which includes by " . $code_line;
                  $took_effect = 0;
                  next;
                }
              }
              if ($include{'main'}) {
                my $break_inc = 0;
                #print "\$path = " . $path;
                #print Dumper(\@include_trace);
                foreach my $inc (@include_trace) {
                  if ($inc eq $path) {
                    $break_inc = 1;
                    print STDERR "WARNING: Never-ending inclusion loop detected.";
                    last;
                  }
                }
                if ($break_inc == 1) {
                  $took_effect = 0;
                  next;
                }
                @tmp2 = &read_code($include{'main'});
                #print @tmp2;
                #print Dumper(\@tmp2);
                if (@tmp2 > 0) {
                  push @tmp, @tmp2;
                  push @include_trace, $path;
                  $took_effect = 1;
                } else {
                  $took_effect = 0;
                }
              }
            }
          } else {
            push @tmp, $code_line;
          }
        }
        @code_lines = @tmp;
      }
      # here the code will be completed by includes.
      # let's compose all function calls. methods inside classes and static classes.
      #print Dumper(\@code_lines);
      # we need to interprete first code inclusions, to be able to replace function calls
      # after, and don't complicate things so much.
      # first we match all classes instantiations stored in variables to be able to locate, the following
      # methods calls.
      # for the v1.0.0-rc4 namespaces are not implemented. So new classes instantiatons will be searched as its definition's name.
      # this kind of classes' methods calls are implemented:
      # $class->func(); // dynamic class method
      # Class::func(); // static class method
      #
      # A method of a class renamed, or referenced from other namespace is not supported and will warn.
      my %insts = ();
      foreach my $code_line (@code_lines) {
        if ($code_line =~ m/^[\s]*(\$[^\s]+)[\s]*\=[\s]*new[\s]+([^\(\s]+)[^;]+;/i) {
          # we got a class instantiation and store in variable: $class = new Class();
          my $var_name = $1;
          my $class_name = $2;
          #print "Class declaration: $var_name = $class_name";
          $insts{$var_name} = $class_name;
        }
      }
      #print Dumper(\%insts);
      # now we search for simple function call:
      # func();
      # later we search for static and dynamic classes' methods call.
      # we'll recognize first those lines whom matches with re of a call.
      # it should return true on simple calls, methods of static and dynamic classes.
      # Later we parse nested calls by harcoding prg.
      # When a call is found, the function's code is replaced by the call. That makes easy prg when searching vulns, because
      # we can implement a batch of REs to be applied in order. Not giving chance to enter in dedicated logics.
      # In the case of nested calls, the alg is looped until all nested loops are replaced.
      # The replacing of a simple call is easy:
      # 1. we have:
      # func();
      # function func () {
      #   return "hola";
      # }
      #
      # 2. replaced by:
      #
      # $randomVar = "hola";
      #
      # When is a nested loop:
      #
      # 1. we have:
      # func(func2());
      #
      # function func($param) { return $param; }
      # function func2() { return "HOLA"; }
      #
      # 2.1 we obtain in first loop:
      # $randomVar = "HOLA";
      # func($randomVar);
      #
      # 2.2 we obtain in second loop:
      # $randomVar1 = "HOLA";
      # $randomVar2 = $randomVar1;
      foreach my $code_line (@code_lines) {
        my $tmp_line = $code_line;
        my $match = 1;
        my $nested_calls = 0;
        while ($match == 1) {
          $match = 0;
          if ($tmp_line =~ m/([a-z0-9\_]+)[\s]*\(([\s\S]+)\)[\s]*;/gi) {
            # we match a call, so we extract params and re-match if nested call.
            my $tmp_line2 = $2;
            if ($tmp_line2 =~ m/([a-z0-9\_]+)[\s]*\(([\s\S]+)\)[\s]*;/gi) {
              $match = 1;
            } else {
              $match = 0;
            }
          } else {
            # this means the latest call was the last nested call.
          }
        }
      }
    }
  }
}

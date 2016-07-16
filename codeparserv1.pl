#!/usr/bin/perl -l
#
use strict;
use warnings;
use File::Basename;
use Data::Dump qw(dump);
use threads;
use threads::shared;
use JSON;

our @code_json :shared = ();

die "use: $0 <source_code_dir1> [<source_code_dir2> <...> <source_code_dirN>] <output_file> <workers>" unless @ARGV >= 3;

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
    # print "CLASS BLOCK BEGIN";
    # print $class_block;
    # print "CLASS BLOCK END";
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
  $code_chunk{$file}{'main'} = $buf;
  #match all beggining of functions for reading each one
  #@tmp = $buf =~ m/(function[\s]+\w+?[\s]*\([^\)]*[\s]*\)[\s]*\{)/gi;
  #&read_blocks(@tmp);
  #
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
  foreach my $dir (@$dirs_ref) {
    print "Reading Software directory " . $dir . " ...";
    push @files, &read_code_dir($dir);
  }
  return @files;
}

my $workers = pop @ARGV;
my $out_file = pop @ARGV;
my @files:shared;
@files = &read_code_dirs(\@ARGV);

my @threads = ();
for (my $wid = 1; $wid <= $workers; $wid++) {
  my $th = threads->create(sub {
    my ($mce) = @_;

    print "Worker " . $wid . " is launched and parsing code ...";
    while (@files > 0) {
      my $file;
      {
        lock @files;
        $file = pop @files;
      }
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
my %final_hash = ();
print "Composing final hash ...";
$chunk_json = pop @code_json;
while ($chunk_json) {
  $chunk_json = pop @code_json;
  my $hash_ref = decode_json $chunk_json;
  foreach my $file (keys $hash_ref) {
    $final_hash{$file} = \$hash_ref->{$file};
  }
}

my $out_package = "";
my @out_package_dir = split(/\//, $out_file);
foreach my $out_package_dir (@out_package_dir) {
  if ($out_package_dir) {
    $out_package .= $out_package_dir . "::";
  }
}
$out_package =~ s/\.pm\:\:$//;
print "Saving parsed software structure as package " . $out_package . " into " . $out_file . " ...";
open my $fh, ">", $out_file or die "open: $!";
print $fh "#!/usr/bin/perl -l\n";
print $fh "package " . $out_package . ";\n";
print $fh "use strict;\nuse warnings;\nuse Data::Dump qw(dump);\nmy \%code = ";
print $fh dump(%final_hash);
print $fh ";";
#print $fh "\"$out_package\"";
#print $fh "; print dump(\%code);";
close $fh;

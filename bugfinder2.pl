#!/usr/bin/perl -l
#
use strict;
use warnings;
use File::Basename;
use Data::Dump qw(dump);

die "use: $0 <wp_dir> <plugins_dir>" unless @ARGV > 1;

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
  my $buf = $_[3];

  my @blocks = ();
  #my @tmp = $buf =~ /$rx/gi;
  #for (my $pointer = 0; $pointer < length($buf); $pointer += $i) {
  #foreach my $tmp2 (@tmp) {
  #while ($i < length($buf)) {
    #next if (! defined $tmp2);
    my $code = "";
    #my $pointer = index($buf, $tmp2);
    #if ($pointer == -1) {
    #  warn "that shouldn't be said.";
    #}
    my $c = 0;
    my $open_c = 0;
    my $close_c = 0;
    my $ignore_s = 0;
    my $ignore_d = 0;
    my $ignore_t = 1;
    my $escaped = 0;
    my $copy = 0;
    for (my $i = 0; $i < length($buf); $i++) {
      # print $code;
      # print $escaped;
      # print $ignore_s;
      # print $ignore_d;
      # print $close_c;
      # print $open_c;
      # print $i;
      # print length($buf);
      # print "\n";
      $escaped = 0;
      my $prev_c = $c;
      $c = substr($buf, $i, 1);
      if ($prev_c eq '<' && $c eq '?' && $ignore_s == 0 && $ignore_d == 0) {
        if ($copy == 1) {
          $code = substr($code, 0, length($code) - 1);
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
        }
        while(!($prev_c eq '?' && $c eq '>') && !($c eq "\n") && !($i >= length($buf))) {
          $i++;
          $prev_c = $c;
          $c = substr($buf, $i, 1);
        }
        next;
      }
      if ($c eq '#' && $ignore_s == 0 && $ignore_d == 0 && $escaped == 0) {
        # shardp single line comment begins
        while($c ne "\n" && $i < length($buf)) {
          $prev_c = $c;
          $c = substr($buf, $i, 1);
          $i++;
        }
        next;
      }
      # if (substr($buf, $i, length($rx)) eq $rx && $ignore_s == 0 && $ignore_d == 0) {
      #   print "$open_c - $close_c";
      # }
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
          $copy = 1;
        }
        $i = $tmp;
        $c = $tmp2;
        #$i = $tmp;
        #$c = $tmp2;
        # my $class_name = "";
        # while ($c ne ' ' && $c ne '{') {
        #   $c = substr($buf, $i, 1);
        #   $i++;
        #   $class_name .= $c;
        # }
        # while ($c eq ' ' || $c eq "\t") {
        #   $c = substr($buf, $i, 1);
        #   $i++;
        #   $class_name .= $c;
        # }
        # if ($c == '{') {
          # $i = $tmp;

          #print "HOLA";
          #print substr($buf, $i, 100);
        # }
      }
      if ($prev_c eq '<' && $c eq '<' && substr($buf, $i + 1, 1) eq '<' && $ignore_s == 0 && $ignore_d == 0) {
        if ($copy == 1) {
          $code .= '<<';
        }
        $i += 2;
        my $tag_start_i = $i;
        while ($c ne "\n" && $i < length($buf)) {
          $c = substr($buf, $i, 1);
          if ($copy == 1) {
            $code .= $c;
          }
          $i++;
        }
        my $tag_end_i = $i - 1;
        substr($buf, $tag_start_i, $tag_end_i - $tag_start_i) =~ m/[\'\"]*(\w+)[\'\"]*/;
        my $tag = $1;
        #print $tag;
        my $tmp_buf = substr($buf, $i, length($buf) - $i);
        my $block_end_i = $i + index($tmp_buf, "\n$tag;");
        for (; $i < $block_end_i; $i++) {
          if ($copy == 1) {
            $code .= substr($buf, $i, 1);
          }
        }
        #print $code;
      }
      if ($c eq '\\') {
        my $e_times = 0;
        while ($c eq '\\' && $i < length($buf)) {
          if ($copy == 1) {
            $code .= $c;
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
      }
      # print $code;
      # print $escaped;
      # print $ignore_s;
      # print $ignore_d;
      #print $close_c;
      #print $open_c;
      # print $i;
      # print length($buf);
      # print "\n";

      if ($close_c > 0 && $close_c == $open_c) {
        #print $close_c;
        #print $open_c;
        # print "CLOSE";
        # print $code;
        $copy = $open_c = $close_c = 0;
        if ($code ne "") {
          push @blocks, $code;
          $code = "";
        }
      }
    }
    #print $code;
    # now $code has the whole function declaration
    #print "ONE BLOCK";
    #print $code;
    #print "END BLOCK";
    # next if $code eq "";
    # push @blocks, $code;
  #}
  return @blocks;
}

sub load_wp_script {
  my $file = $_[0];
  my $buf = &file_get_contents($file);
  my %out;
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
  my @class_blocks = &read_blocks('class ', qr/(class[\s]*[\w\_]+?[\s]+extends[\s]+[\w\_]+?[\s]+implements[\s]+[\w\_]+[\s]*\{)|(class[\s]*[\w\_]+?[\s]+extends[\s]+[\w\_]+?[\s]*\{)|(class[\s]*[\w\_]+?[\s]+implements[\s]+[\w\_]+?[\s]*\{)|(class[\s]*[\w\_]+?[\s]*\{)/i, 0, $buf);
  # print "@class_blocks";
  # die;
  foreach my $class_block (@class_blocks) {
    # print "CLASS BLOCK BEGIN";
    # print $class_block;
    # print "CLASS BLOCK END";
    my ($class_name, $class_code, $parent_class, $interface);
    ($class_name, $class_code) = $class_block =~ m/^class[\s]*([\w\_]+)?[\s]*\{([\s\S]*)\}/gi;
    ($class_name, $parent_class, $class_code) = $class_block =~ m/^class[\s]*([\w+\_]+)?[\s]+extends[\s]+([\w\_]+)?[\s]*\{([\s\S]*)\}/gi if (!defined $class_name);
    ($class_name, $interface, $class_code) = $class_block =~ m/^class[\s]*([\w+\_]+)?[\s]+implements[\s]+([\w\_]+)?[\s]*\{([\s\S]*)\}/gi if (!defined $class_name);
    ($class_name, $parent_class, $interface, $class_code) = $class_block =~ m/^class[\s]*([\w+\_]+)?[\s]+extends[\s]+([\w\_]+)?[\s]*implements[\s]+([\w+\_]+)?[\s]*\{([\s\S]*)\}/gi if (!defined $class_name);
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
    my @func_blocks = &read_blocks('function ', qr/(function[\s]+([\&\w\_]+)[\s]*\(([^{]*)\)[\s]*\{)/i, 0, '<?php '.$class_code);
    my %class_methods;
    foreach my $func_block (@func_blocks) {
      my ($func_name, $func_args, $func_code) = $func_block =~ m/function[\s]+([\&\w]+)[\s]*\(([^{]*)[\s]*\)[\s]*\{([\s\S]*)}/gi;
      if (!defined $func_name) {
        print $file;
        print $func_block;
        #print $class_block;
        die;
      }
      $class_methods{$func_name} = {
        'args' => $func_args,
        'code' => $func_code,
      };
    }
    $out{'classes'}{$class_name} = {
      'parent' => $parent_class,
      'interface' => $interface,
      #'code' => $class_code,
      'methods' => \%class_methods,
    };
  }
  #match all beggining of functions for reading each one
  #@tmp = $buf =~ m/(function[\s]+\w+?[\s]*\([^\)]*[\s]*\)[\s]*\{)/gi;
  #&read_blocks(@tmp);
  return %out;
}

sub load_wp_scripts {
  my $dir = $_[0];
  my $tmp = $_[1];
  my %wpcode = %$tmp;
  opendir(my $dh, $dir) || die "Can't opendir $dir $!";
  while (readdir($dh)) {
    if (-f "$dir/$_" && $_ =~ /\.php$/) {
      my %tmp = &load_wp_script("$dir/$_");
      next if (!%tmp);
      foreach my $key (keys $tmp{'classes'}) {
        $wpcode{'classes'}{$key} = $tmp{'classes'}{$key};
      }
      #$wpcode{'functions'} = ($wpcode{'functions'}, $tmp{'functions'});
      #$wpcode{'main'} = ($wpcode{'main'}, $tmp{'main'});
    } elsif (-d "$dir/$_" && $_ ne "." && $_ ne "..") {
      %wpcode = &load_wp_scripts("$dir/$_", \%wpcode);
    }
  }
  closedir $dh;
  return %wpcode;
}

sub read_wp_dir {
  my $dir = $_[0];
  my @files = ();
  opendir(my $dh, $dir) || die "Can't opendir $dir $!";
  while (readdir($dh)) {
    if (-f "$dir/$_" && $_ =~ /\.php$/) {
      push @files, "$dir/$_";
    } elsif (-d "$dir/$_" && $_ ne "." && $_ ne "..") {
      my @tmp = &read_wp_dir("$dir/$_");
      push @files, @tmp;
    }
  }
  closedir $dh;
  return @files;
}

my %wpcode;
my @files;
print "Reading WordPress directory ...";
@files = &read_wp_dir($ARGV[0]);
print "Loading WordPress source code ...";
%wpcode = &load_wp_scripts($ARGV[0], \%wpcode);
dump(%wpcode);

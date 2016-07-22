my 
$VAR1 = {
          'bug-examples/good/library/recursive.php' => {
                                                         'main' => '

include("recursive.php");
 '
                                                       },
          'bug-examples/good/library/example12.class.php' => {
                                                               'classes' => {
                                                                              'Example12_2' => {
                                                                                                 'parent' => undef,
                                                                                                 'methods' => {
                                                                                                                'include_code' => {
                                                                                                                                    'code' => '
    $var = "string". $var;
    $var2 = "string". $var;
    include($var2);
  ',
                                                                                                                                    'args' => '$var'
                                                                                                                                  },
                                                                                                                'get_var' => {
                                                                                                                               'args' => '',
                                                                                                                               'code' => '
    return $_REQUEST[\'var1\'];
  '
                                                                                                                             }
                                                                                                              },
                                                                                                 'interface' => undef
                                                                                               },
                                                                              'Example12_1' => {
                                                                                                 'interface' => undef,
                                                                                                 'methods' => {
                                                                                                                'include_code1' => {
                                                                                                                                     'code' => '
    $var = "string". $var;
    $var2 = "string". $var;
    include($var2);
  ',
                                                                                                                                     'args' => '$var'
                                                                                                                                   },
                                                                                                                'get_var3' => {
                                                                                                                                'args' => '',
                                                                                                                                'code' => '
    return parent::get_var();
  '
                                                                                                                              },
                                                                                                                'include_code3' => {
                                                                                                                                     'args' => '$var',
                                                                                                                                     'code' => '
    $var = "string". $var;
    $var2 = "string". $var;
    parent::include_code($var);
  '
                                                                                                                                   },
                                                                                                                'get_var2' => {
                                                                                                                                'code' => '
    return $_GET[\'var2\'];
  ',
                                                                                                                                'args' => ''
                                                                                                                              },
                                                                                                                'get_var1' => {
                                                                                                                                'args' => '',
                                                                                                                                'code' => '
    return $_REQUEST[\'var1\'];
  '
                                                                                                                              },
                                                                                                                'include_code2' => {
                                                                                                                                     'args' => '$var',
                                                                                                                                     'code' => '
    $var = "string". $var;
    $var2 = "string". $var;
    self::include_code1($var);
  '
                                                                                                                                   }
                                                                                                              },
                                                                                                 'parent' => 'Example12_2'
                                                                                               }
                                                                            },
                                                               'main' => '





class_12();
 '
                                                             },
          'bug-examples/good/library/example11.class.php' => {
                                                               'classes' => {
                                                                              'Example11_2' => {
                                                                                                 'interface' => undef,
                                                                                                 'parent' => undef,
                                                                                                 'methods' => {
                                                                                                                'include_code' => {
                                                                                                                                    'code' => '
    include($var);
  ',
                                                                                                                                    'args' => '$var'
                                                                                                                                  },
                                                                                                                'get_var' => {
                                                                                                                               'args' => '',
                                                                                                                               'code' => '
    return $_REQUEST[\'var\'];
  '
                                                                                                                             }
                                                                                                              }
                                                                                               },
                                                                              'Example11_1' => {
                                                                                                 'parent' => 'Example11_2',
                                                                                                 'methods' => {
                                                                                                                'get_var1' => {
                                                                                                                                'args' => '',
                                                                                                                                'code' => '
    return $_REQUEST[\'var1\'];
  '
                                                                                                                              },
                                                                                                                'get_var2' => {
                                                                                                                                'code' => '
    return $_GET[\'var2\'];
  ',
                                                                                                                                'args' => ''
                                                                                                                              },
                                                                                                                'include_code2' => {
                                                                                                                                     'args' => '$var',
                                                                                                                                     'code' => '
    $var = "string". $var;
    $var2 = "string". $var;
    $this->include_code($var);
  '
                                                                                                                                   },
                                                                                                                'include_code1' => {
                                                                                                                                     'code' => '
    $var = "string". $var;
    $var2 = "string". $var;
    include($var2);
  ',
                                                                                                                                     'args' => '$var'
                                                                                                                                   },
                                                                                                                'get_var3' => {
                                                                                                                                'code' => '
    return $this->get_var();
  ',
                                                                                                                                'args' => ''
                                                                                                                              }
                                                                                                              },
                                                                                                 'interface' => undef
                                                                                               }
                                                                            },
                                                               'main' => '



class_11();
'
                                                             }
        };

%library = %{$VAR1};

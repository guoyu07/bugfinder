<?php
class myclass {

  public function myfunc() {
    echo "}";
    echo "\"}";
    echo '}';
    echo '\'}';
?>
<?php
    $var = "hola";
    echo <<<PRE
$var Abel.

PRE;
    echo <<<"PRE"
$var Abel.

PRE;
    echo <<<'PRE'
$var Abel.

PRE;
  }
        private function _escape($string, $escapeChar) {
                if ($escapeChar) {
                        $this->buffer = $escapeChar;
                        return preg_replace_callback(
                                '/\\' . $escapeChar . '(.)' .'/',
                                array(&$this, '_escapeBis'),
                                $string
                        );

                } else {
                        return $string;
                }
        }

}

class MyClass {
    const CONST_VALUE = 'Un valor constante';
}

class OtherClass extends MyClass
{
    public static $my_static = 'variable estática';

    public static function doubleColon() {
        echo parent::CONST_VALUE . "\n";
        echo self::$my_static . "\n";
    }
}

class MyClass
{
  ?><?
  function wp_remote_retrieve_headers(&$response) {
  if ( is_wp_error($response) || ! isset($response['headers']) || ! is_array($response['headers']))
          return array();

  return $response['headers'];
  }
  ?><?
    protected function myFunc() {
      ?>
      <html>
      </html>
      <?
    }
}

class OtherClass extends MyClass
{
    // Sobrescritura de definición parent
    public function myFunc()
    {
        // Pero todavía se puede llamar a la función parent
        parent::myFunc();
        echo "OtherClass::myFunc()\n";
    }
}

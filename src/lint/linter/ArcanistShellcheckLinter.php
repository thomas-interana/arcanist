<?php

/**
 * Uses koala_man's shellcheck to check shell scripts.
 */
final class ArcanistShellcheckLinter extends ArcanistExternalLinter {

  public function getLinterName() {
    return 'koala_man\'s shellcheck';
  }

  public function getLinterConfigurationName() {
    return 'shellcheck';
  }

  public function getDefaultBinary() {
    return 'shellcheck';
  }

  public function getMandatoryFlags() {
    return array('-fjson');
  }

  public function getInstallInstructions() {
    return pht('Install shellcheck by using %s (or equivalent)',
      'apt-get install shellcheck');
  }

  protected function getDefaultMessageSeverity($code) {
    return ArcanistLintSeverity::SEVERITY_WARNING;
  }

  protected function parseLinterOutput($path, $err, $stdout, $stderr) {
    $lines = json_decode($stdout, true);

    $messages = array();
    foreach ($lines as $line) {
      $message = new ArcanistLintMessage();
      $message->setPath($path);
      $message->setLine($line['line']);
      $message->setCode($line['code']);
      $message->setName($line['message']);
      $message->setSeverity($this->getLintMessageSeverity($line['level']));

      $messages[] = $message;
    }

    return $messages;
  }

}

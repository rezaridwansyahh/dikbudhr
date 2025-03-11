<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config_old = array(
    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
    'smtp_host' => 'ssl://mail.kemdikbud.go.id', 
    //'smtp_host'=> 'ssl://118.98.222.90',
    'smtp_port' => 465,
    'smtp_user' => '*******@kemdikbud.go.id',
    'smtp_pass' => '*******',
    'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
    'mailtype' => 'html', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '120', //in seconds
    'charset' => 'iso-8859-1',
    'wordwrap' => TRUE
);

$config = array(
    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
    'smtp_host' => 'mail.kemdikbud.go.id', 
    'smtp_port' => 465,
    'smtp_user' => '*****@kemdikbud.go.id',
    'smtp_pass' => '******',
    'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
    'mailtype' => 'text', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '120', //in seconds
    'charset' => 'iso-8859-1',
    'newline' => "\r\n",
'crlf' => "\r\n",
    'wordwrap' => TRUE
);

<?php 
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\POP3;
    use PHPMailer\PHPMailer\OAuthTokenProvider;

    require './PHPMailer/src/Exception.php';
    require './PHPMailer/src/PHPMailer.php';
    require './PHPMailer/src/SMTP.php';
    require './PHPMailer/src/POP3.php';
    require './PHPMailer/src/OAuthTokenProvider.php';

    class Mensagem{
        private $destinatario = null;
        private $assunto = null;
        private $msg = null;
        public $status = ['codigoStatus' => null, 'descricaoStatus' => ''];

        public function __construct($destinatario, $assunto, $msg){
            $this->destinatario = $destinatario;
            $this->assunto = $assunto;
            $this->msg = $msg;
        }

        public function __set($attr, $valor){
            $this->$attr = $valor;
        }

        public function __get($attr){
            return $this->$attr;
        }

        public function __mensagemValida(){
            if(!empty($this->destinatario) && !empty($this->assunto) && !empty($this->msg)){
                return true;
            }
            return false;
                
            
        }
    }
    $mensagem = new Mensagem($_POST['destinatario'], $_POST['assunto'], $_POST['msg']);    
      
    if(!$mensagem->__mensagemValida()){
        header('location: index.php?erro=Errot1');
        die();
    }

    $mail = new PHPMailer(true);

    try {
        
        $mail->SMTPDebug = false;                      
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com';                     
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'gamenizados@gmail.com';                     
        $mail->Password   = 'uttkgkmfsusemsot';                               
        $mail->SMTPSecure = 'tls';
        $mail->port = 587;            
    
        $mail->setFrom('gamenizados@gmail.com', 'Mensagem Enviada');
        $mail->addAddress($mensagem->__get('destinatario'), 'Destinatario');    
    
        $mail->isHTML(true);                                  
        $mail->Subject = $mensagem->__get('assunto');
        $mail->Body    = $mensagem->__get('msg');
        $mail->AltBody = "Seu client não rederiza HTML, mensagem Invalidada";
    
        $mail->send();
        $mensagem->status['codigoStatus'] = 1;
        $mensagem->status['descricaoStatus'] = 'Email enviado com sucesso';
    } catch (Exception $e) {
        $mensagem->status['codigoStatus'] = 2;
        $mensagem->status['descricaoStatus'] = 'Não foi possivel enviar esse email, tente mais tarde!!' . $mail->ErrorInfo;
        echo " Error: {$mail->ErrorInfo}";
    }
    

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Send Mail</title>
</head>
<body>
    <div class="container">
        <div class="py-3 text-center">
            <img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
            <h2>Send Mail</h2>
            <p class="lead">Seu app de envio de e-mails particular!</p>
        </div>
        <div class="row">
            <div class="col-md-12">

                <?php if($mensagem->status['codigoStatus'] == 1){?>
                    <div class="container">
                        <h1 class="display4 text-success"> Sucesso </h1>
                        <p><?php echo $mensagem->status['descricaoStatus'] ;?></p>
                        <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                    </div>
               <?php } ?>
               <?php if($mensagem->status['codigoStatus'] == 2){?>
                <div class="container">
                        <h1 class="display4 text-danger "> Erro </h1>
                        <p><?php echo $mensagem->status['descricaoStatus'] ;?></p>
                        <a href="index.php" class="btn btn-danger btn-lg mt-5 text-white">Voltar</a>
                    </div>
               <?php } ?>
               

            </div>
        </div>
    </div>
</body>
</html>
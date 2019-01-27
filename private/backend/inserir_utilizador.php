<?php
include_once('../../ConnectDB.php');
require('../../Email.php');

// importante ver os erros do php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_POST['submitButton'])){ //check if form was submitted

    $connection = ConnectDB::getInstance()->getConnection();
    $data['nome'] = $_POST['nome'];
    $data['nif'] = $_POST['nif'];
    $data['cc'] = isset($_POST['cc']) ? $_POST['cc'] : null;
    $data['datan'] = $_POST['datan'];
    $data['email'] = $_POST['email'];
    $data['telef'] = isset($_POST['telef']) ? $_POST['telef'] : null;
    $data['morada'] = $_POST['morada'];
    $data['nacionalidade'] = isset($_POST['nacionalidade']) ? $_POST['nacionalidade'] : null ;
    $data['genero'] = $_POST['genero'];
    $data['activo'] = isset($_POST['activo']) ? 1 : 0;
    $data['federado'] = isset($_POST['federado']) ? 1 : 0;
    $data['tempos'] = $_POST['tempos'];
    $data['tamanho'] = $_POST['tamanho'];
    $data['senha'] = $_POST['senha'];
    criarUtilizador($data, $connection);
}

function criarUtilizador($data, $connection)
{

    $email   = mysqli_real_escape_string($connection, $data['email']);

    if(emailExiste($email, $connection)){
        header("location:index.php?novo_utilizador=false&mensagem=existe");
        exit();
    }

    $nome    = mysqli_real_escape_string($connection, $data['nome']);
    $nif     = mysqli_real_escape_string($connection, $data['nif']);
    $cc      = mysqli_real_escape_string($connection, $data['cc']);
    $datan   = mysqli_real_escape_string($connection, $data['cc']);$data['datan'];

    $telef   = mysqli_real_escape_string($connection, $data['telef']);
    $morada  = mysqli_real_escape_string($connection, $data['morada']);
    $nacao   = mysqli_real_escape_string($connection, $data['nacionalidade']);
    $genero  = mysqli_real_escape_string($connection, $data['genero']);
    $activo  = mysqli_real_escape_string($connection, $data['activo']);
    $federado = mysqli_real_escape_string($connection, $data['federado']);
    $tempos  = mysqli_real_escape_string($connection, $data['tempos']);
    $tamanho = mysqli_real_escape_string($connection, $data['tamanho']);
    $senha = sha1(mysqli_real_escape_string($connection, $data['senha']));

    //$senha = gerarPassword();
    //$pass_cifrada = sha1($senha);


    $query = "INSERT INTO utilizador(nome, nif, cc, datan, email, telef, senha, morada, nacionalidade, genero, tempos,
 tamanho, ativo, federado)VALUES('$nome', '$nif', '$cc', '$datan', '$email', '$telef', '$senha','$morada','$nacao', '$genero', 
 '$tempos', '$tamanho', '$activo', '$federado')";


    $user  = mysqli_query($connection , $query);
    mysqli_close($connection);

    if($user)
    {
        # tratar do acesso à internet no container
        //enviarEmail($email, $nome, $senha);

        header("location:index.php?novo_utilizador=true");
    }
    else
    {
        echo("Houve um erro ao introduzir o utilizador!");
        print_r(mysqli_error_list($connection));
    }
}

function emailExiste($email, $connection){

    $query = "SELECT * FROM utilizador WHERE email = '".$email."'";
    $result = mysqli_query($connection, $query);
    if(mysqli_num_rows($result) > 0)
      return true;

    return false;
}

function gerarPassword(){

    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function enviarEmail($email, $nome, $senha){
    /**
     * Esta função envia um email para o utilizador indicando-lhe a password que foi gerada
     */
    $mail = new EMail();
    $mail->Username = 'euroupgrid@gmail.com';
    $mail->Password = 'gwdpp753001!';

    $mail->SetFrom("andregarxia@gmail.com","André Garcia");  // Name is optional
    $mail->AddTo($email);
    $mail->Subject = "Registo efectuado";
    $mail->Message = "Olá " + $nome + "<br><br> A sua senha para acesso à plataforma é: " + $senha;

    //Optional stuff
    $mail->ContentType = "text/html";          // Defaults to "text/plain; charset=iso-8859-1"
    $mail->Headers['X-SomeHeader'] = 'abcde';  // Set some extra headers if required
    $mail->ConnectTimeout = 30;  // Socket connect timeout (sec)
    $mail->ResponseTimeout = 8;  // CMD response timeout (sec)

    $mail->Send();
}

include('include/header.php'); ?>
<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <?php include('include/sidebar.php')?>
        </div>
<div class="col-sm-9">
    <h2 class="titulo" style="margin-top: 20px;">Inserir utilizador</h2>
    <form role="form" action="" method="POST">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                <input type="text" name="nome" id="first_name" class="form-control input-sm" placeholder="Nome" required>
                </div>
            </div>
          <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <select class="form-control" name="genero" id="genero">
                      <option value="Masculino">Masculino</option>
                      <option value="Feminino">Feminino</option>
                    </select>
                </div>
          </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <input type="text" name="nif" id="nif" class="form-control input-sm" placeholder="NIF" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                <input type="text" name="cc" id="cc" class="form-control input-sm" placeholder="CC" required>
                </div>
            </div>
          <div class="col-xs-3 col-sm-3 col-md-2">
            <small>Data de Nascimento:</small>
        </div>
            <div class="col-xs-12 col-sm-5 col-md-4">
                <div class="form-group">
                    <input type="date" name="datan" id="datan" class="form-control input-sm" placeholder="Data de nascimento" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <input type="email" name="email" id="email" class="form-control input-sm" placeholder="Email" required>
        </div>

        <div class="form-group">
            <input type="text" name="morada" id="morada" class="form-control input-sm" placeholder="Morada" required>
        </div>

        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                <input type="text" name="telef" id="telef" class="form-control input-sm" placeholder="Telefone" required>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <select class="form-control" name="nacionalidade" id="nacionalidade">
                      <option value="" disabled selected>Nacionalidade</option>
                      <option value="portuguese">Portuguese</option>
                      <option value="afghan">Afghan</option>
                      <option value="albanian">Albanian</option>
                      <option value="algerian">Algerian</option>
                      <option value="american">American</option>
                      <option value="andorran">Andorran</option>
                      <option value="angolan">Angolan</option>
                      <option value="antiguans">Antiguans</option>
                      <option value="argentinean">Argentinean</option>
                      <option value="armenian">Armenian</option>
                      <option value="australian">Australian</option>
                      <option value="austrian">Austrian</option>
                      <option value="azerbaijani">Azerbaijani</option>
                      <option value="bahamian">Bahamian</option>
                      <option value="bahraini">Bahraini</option>
                      <option value="bangladeshi">Bangladeshi</option>
                      <option value="barbadian">Barbadian</option>
                      <option value="barbudans">Barbudans</option>
                      <option value="batswana">Batswana</option>
                      <option value="belarusian">Belarusian</option>
                      <option value="belgian">Belgian</option>
                      <option value="belizean">Belizean</option>
                      <option value="beninese">Beninese</option>
                      <option value="bhutanese">Bhutanese</option>
                      <option value="bolivian">Bolivian</option>
                      <option value="bosnian">Bosnian</option>
                      <option value="brazilian">Brazilian</option>
                      <option value="british">British</option>
                      <option value="bruneian">Bruneian</option>
                      <option value="bulgarian">Bulgarian</option>
                      <option value="burkinabe">Burkinabe</option>
                      <option value="burmese">Burmese</option>
                      <option value="burundian">Burundian</option>
                      <option value="cambodian">Cambodian</option>
                      <option value="cameroonian">Cameroonian</option>
                      <option value="canadian">Canadian</option>
                      <option value="cape verdean">Cape Verdean</option>
                      <option value="central african">Central African</option>
                      <option value="chadian">Chadian</option>
                      <option value="chilean">Chilean</option>
                      <option value="chinese">Chinese</option>
                      <option value="colombian">Colombian</option>
                      <option value="comoran">Comoran</option>
                      <option value="congolese">Congolese</option>
                      <option value="costa rican">Costa Rican</option>
                      <option value="croatian">Croatian</option>
                      <option value="cuban">Cuban</option>
                      <option value="cypriot">Cypriot</option>
                      <option value="czech">Czech</option>
                      <option value="danish">Danish</option>
                      <option value="djibouti">Djibouti</option>
                      <option value="dominican">Dominican</option>
                      <option value="dutch">Dutch</option>
                      <option value="east timorese">East Timorese</option>
                      <option value="ecuadorean">Ecuadorean</option>
                      <option value="egyptian">Egyptian</option>
                      <option value="emirian">Emirian</option>
                      <option value="equatorial guinean">Equatorial Guinean</option>
                      <option value="eritrean">Eritrean</option>
                      <option value="estonian">Estonian</option>
                      <option value="ethiopian">Ethiopian</option>
                      <option value="fijian">Fijian</option>
                      <option value="filipino">Filipino</option>
                      <option value="finnish">Finnish</option>
                      <option value="french">French</option>
                      <option value="gabonese">Gabonese</option>
                      <option value="gambian">Gambian</option>
                      <option value="georgian">Georgian</option>
                      <option value="german">German</option>
                      <option value="ghanaian">Ghanaian</option>
                      <option value="greek">Greek</option>
                      <option value="grenadian">Grenadian</option>
                      <option value="guatemalan">Guatemalan</option>
                      <option value="guinea-bissauan">Guinea-Bissauan</option>
                      <option value="guinean">Guinean</option>
                      <option value="guyanese">Guyanese</option>
                      <option value="haitian">Haitian</option>
                      <option value="herzegovinian">Herzegovinian</option>
                      <option value="honduran">Honduran</option>
                      <option value="hungarian">Hungarian</option>
                      <option value="icelander">Icelander</option>
                      <option value="indian">Indian</option>
                      <option value="indonesian">Indonesian</option>
                      <option value="iranian">Iranian</option>
                      <option value="iraqi">Iraqi</option>
                      <option value="irish">Irish</option>
                      <option value="israeli">Israeli</option>
                      <option value="italian">Italian</option>
                      <option value="ivorian">Ivorian</option>
                      <option value="jamaican">Jamaican</option>
                      <option value="japanese">Japanese</option>
                      <option value="jordanian">Jordanian</option>
                      <option value="kazakhstani">Kazakhstani</option>
                      <option value="kenyan">Kenyan</option>
                      <option value="kittian and nevisian">Kittian and Nevisian</option>
                      <option value="kuwaiti">Kuwaiti</option>
                      <option value="kyrgyz">Kyrgyz</option>
                      <option value="laotian">Laotian</option>
                      <option value="latvian">Latvian</option>
                      <option value="lebanese">Lebanese</option>
                      <option value="liberian">Liberian</option>
                      <option value="libyan">Libyan</option>
                      <option value="liechtensteiner">Liechtensteiner</option>
                      <option value="lithuanian">Lithuanian</option>
                      <option value="luxembourger">Luxembourger</option>
                      <option value="macedonian">Macedonian</option>
                      <option value="malagasy">Malagasy</option>
                      <option value="malawian">Malawian</option>
                      <option value="malaysian">Malaysian</option>
                      <option value="maldivan">Maldivan</option>
                      <option value="malian">Malian</option>
                      <option value="maltese">Maltese</option>
                      <option value="marshallese">Marshallese</option>
                      <option value="mauritanian">Mauritanian</option>
                      <option value="mauritian">Mauritian</option>
                      <option value="mexican">Mexican</option>
                      <option value="micronesian">Micronesian</option>
                      <option value="moldovan">Moldovan</option>
                      <option value="monacan">Monacan</option>
                      <option value="mongolian">Mongolian</option>
                      <option value="moroccan">Moroccan</option>
                      <option value="mosotho">Mosotho</option>
                      <option value="motswana">Motswana</option>
                      <option value="mozambican">Mozambican</option>
                      <option value="namibian">Namibian</option>
                      <option value="nauruan">Nauruan</option>
                      <option value="nepalese">Nepalese</option>
                      <option value="new zealander">New Zealander</option>
                      <option value="ni-vanuatu">Ni-Vanuatu</option>
                      <option value="nicaraguan">Nicaraguan</option>
                      <option value="nigerien">Nigerien</option>
                      <option value="north korean">North Korean</option>
                      <option value="northern irish">Northern Irish</option>
                      <option value="norwegian">Norwegian</option>
                      <option value="omani">Omani</option>
                      <option value="pakistani">Pakistani</option>
                      <option value="palauan">Palauan</option>
                      <option value="panamanian">Panamanian</option>
                      <option value="papua new guinean">Papua New Guinean</option>
                      <option value="paraguayan">Paraguayan</option>
                      <option value="peruvian">Peruvian</option>
                      <option value="polish">Polish</option>
                      <option value="qatari">Qatari</option>
                      <option value="romanian">Romanian</option>
                      <option value="russian">Russian</option>
                      <option value="rwandan">Rwandan</option>
                      <option value="saint lucian">Saint Lucian</option>
                      <option value="salvadoran">Salvadoran</option>
                      <option value="samoan">Samoan</option>
                      <option value="san marinese">San Marinese</option>
                      <option value="sao tomean">Sao Tomean</option>
                      <option value="saudi">Saudi</option>
                      <option value="scottish">Scottish</option>
                      <option value="senegalese">Senegalese</option>
                      <option value="serbian">Serbian</option>
                      <option value="seychellois">Seychellois</option>
                      <option value="sierra leonean">Sierra Leonean</option>
                      <option value="singaporean">Singaporean</option>
                      <option value="slovakian">Slovakian</option>
                      <option value="slovenian">Slovenian</option>
                      <option value="solomon islander">Solomon Islander</option>
                      <option value="somali">Somali</option>
                      <option value="south african">South African</option>
                      <option value="south korean">South Korean</option>
                      <option value="spanish">Spanish</option>
                      <option value="sri lankan">Sri Lankan</option>
                      <option value="sudanese">Sudanese</option>
                      <option value="surinamer">Surinamer</option>
                      <option value="swazi">Swazi</option>
                      <option value="swedish">Swedish</option>
                      <option value="swiss">Swiss</option>
                      <option value="syrian">Syrian</option>
                      <option value="taiwanese">Taiwanese</option>
                      <option value="tajik">Tajik</option>
                      <option value="tanzanian">Tanzanian</option>
                      <option value="thai">Thai</option>
                      <option value="togolese">Togolese</option>
                      <option value="tongan">Tongan</option>
                      <option value="trinidadian or tobagonian">Trinidadian or Tobagonian</option>
                      <option value="tunisian">Tunisian</option>
                      <option value="turkish">Turkish</option>
                      <option value="tuvaluan">Tuvaluan</option>
                      <option value="ugandan">Ugandan</option>
                      <option value="ukrainian">Ukrainian</option>
                      <option value="uruguayan">Uruguayan</option>
                      <option value="uzbekistani">Uzbekistani</option>
                      <option value="venezuelan">Venezuelan</option>
                      <option value="vietnamese">Vietnamese</option>
                      <option value="welsh">Welsh</option>
                      <option value="yemenite">Yemenite</option>
                      <option value="zambian">Zambian</option>
                      <option value="zimbabwean">Zimbabwean</option>
            </select>
                </div>
            </div>
        </div>
      <div class="form-group">
            <input type="text" name="tamanho" id="tamanho" class="form-control input-sm" placeholder="Tamanho">
        </div>
      <div class="form-group">
            <input type="text" name="tempos" id="tempos" class="form-control input-sm" placeholder="Tempos">
        </div>
        <div class="form-group">
            <input type="password" name="senha" id="senha" class="form-control input-sm" placeholder="Password">
        </div>

						<div class="form-group">
              <label class="form-check-label">
								<input class="form-check-input" type="checkbox" name="activo" checked>
								<small>Activo</small>
							</label>
              <label class="form-check-label">
								<input class="form-check-input" name="federado" type="checkbox">
								<small>Federado</small>
							</label>

						</div>
      <hr>
						<button type="submit" class="btn btn-default" name="submitButton">Submit</button>

					</form>
</div>
<?php include('include/footer.php'); ?>
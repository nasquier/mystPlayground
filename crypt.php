<?php
if (isset($_POST['login']) AND isset($_POST['pass']))
{
    $login = $_POST['login'];
    $pass_crypte = password_hash($_POST['pass'],PASSWORD_BCRYPT ); // On crypte le mot de passe
    echo '<p>Copy in .htpasswd :<br />' . $login . ':' . $pass_crypte . '</p>';
}

else // On n'a pas encore rempli le formulaire
{
?>

<form method="post">
    <p>
        Login : <input type="text" name="login"><br />
        Password : <input type="text" name="pass"><br /><br />
    
        <input type="submit" value="Encrypt">
    </p>
</form>

<?php
}
?>
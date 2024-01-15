<?php 

    // PAge de Signup

    // On vérifie que le form a été soumis dans un premier temps
    if (isset($_POST['submit'])) {

        // On vérifie que les champs soient bien remplis
        if (!empty($_POST['email']) && !empty($_POST['pseudo']) && !empty($_POST['password']) && !empty($_POST['password-confirm'])) {

            $email = $_POST['email'];
            $pseudo = htmlspecialchars($_POST['pseudo']);
            $password = $_POST['password'];
            $confirm = $_POST['password-confirm'];
           
            $regex = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "L'email n'est pas au bon format";
            } elseif ($password != $confirm) {
                $error = "Les mots de passe doivent etre identiques";
            } elseif (!preg_match($regex, $password)) {
                $error = "Le mot de passe doit contenir au moins 12 caractères, minuscule, majuscule, chiffre et caractère spécial";
            } 

            // Hasher le mot de passe (avat de l'enregistrer en BDD) avec password_hash

            $hash = password_hash($password, PASSWORD_DEFAULT);

            

        
            $connexion = mysqli_connect('localhost', 'root', '', 'signup');

            $verificationemail = "SELECT * FROM signups WHERE email = '$email'";
            $result = $connexion->query($verificationemail);

            if ($result->num_rows > 0) {
                $error = "Cet email est déjà utilisé. Veuillez en choisir un autre.";
            }
            else{
                $nouvelutilisateur = "INSERT INTO signups (email, pseudo, passwords) VALUES ('$email', '$pseudo', '$hash')";
                
                if ($connexion->query($nouvelutilisateur) === TRUE) {
                 
                  header("Location: signed.php");
                } else {
                  $error = "Une erreur s'est produite lors de l'inscription.";
                }
              }
 

        } else {
            $error = "Veuillez remplir tous les champs !";
        }
    }




?> 


    <!-- Formulaire de signup ici  -->
    <form class="space-y-6" method="POST">

      <!-- Label et input email       -->
      <div>
        <label for="email">Email address</label><br>
          <input id="email" name="email" type="text" autocomplete="email">
      </div>

      <!-- Label et input pour le pseudo -->
      <div>
        <label for="pseudo">Pseudo</label><br>
          <input id="pseudo" name="pseudo" type="text" autocomplete="pseudo" >
      </div>

        <!-- Mot de passe et confirmation -->
        <div>
          <label for="password" >Password</label><br>
        </div>

        <div class="mt-2">
          <input id="password" name="password" type="password" autocomplete="current-password" >
        </div>

        <label for="password-confirm">Password Confirmation</label><br>
        <div>
          <input id="password-confirm" name="password-confirm" type="password" autocomplete="current-password" >
        </div>
      </div>

      <?php if (isset($error)) : ?>

        <p><?= $error ?></p>

      <?php endif ?>

      <!-- Bouton de soumission -->
      <div>
        <input type="submit" name="submit" value="Signup">
      </div>

    </form>


  </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<?php

session_start();
if (!isset($_SESSION['user']) || $_SESSION['user'] == "") {
  header("Location: ./login.php");
  exit;
}

/* Connexion à la base de données */
require_once("./connection.php");

/* Action de mise à jour (Update) */
if (isset($_POST['id']) && isset($_POST['username']) && $_POST['btnAction'] == "update") {
  $id = intval($_POST['id']);
  $username = $conn->real_escape_string($_POST['username']);

  // Préparer et exécuter la requête SQL pour mettre à jour le nom d'utilisateur
  $sql = "UPDATE users SET username = '$username' WHERE id = $id";

  if ($conn->query($sql) === TRUE) {
    $msg = "Mise à jour réussie.";
  } else {
    $msg = "Erreur lors de la mise à jour : " . $conn->error;
  }
}

/* Action de suppression (Delete) */

if (isset($_POST['id']) && $_POST['btnAction'] == "delete") {
  $id = intval($_POST['id']);

  $sql = "DELETE FROM users WHERE id = $id";

  if ($conn->query($sql) === TRUE) {
    $msg = "Utilisateur supprimé avec succès.";
  } else {
    $msg = "Erreur lors de la suppression : " . $conn->error;
  }
}

/* Action d'ajout d'utilisateur */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
  $nom_utilisateur = $conn->real_escape_string($_POST["username"]);
  $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Hachage du mot de passe

  $sql = "INSERT INTO users (username, password) VALUES ('$nom_utilisateur', '$password')";

  if ($conn->query($sql) === TRUE) {
    echo "Utilisateur ajouté avec succès.";
  } else {
    echo "Erreur : " . $conn->error;
  }
}
?>

<form action="./logout.php">
  <p class="text-end m-3">
    Bonjour, <?php echo $_SESSION['user']; ?>
    <button type="submit" class="btn btn-light px-3 mx-2">Deconnexion</button>
  </p>
</form>

<!-- Formulaire d'ajout -->
<button onclick="document.getElementById('insertForm').style.display='block'">Ajouter</button>
<div id="insertForm" style="display:none; margin-top: 10px;">
  <form method="POST" action="front2.php">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Enregistrer</button>
  </form>
</div>

<?php
// Récupérer les utilisateurs pour afficher dans un tableau
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
?>
  <!-- Affichage du message de succès ou d'erreur -->
  <p class="text-success text-center"><?php echo $msg ?? ''; ?></p>
  <table class="table table-bordered text-center">
    <thead>
      <tr>
        <th>Id</th>
        <th>Nom d'utilisateur</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="userTable">
      <?php
      while ($row = $result->fetch_array()) {
        echo "<tr id='userRow" . $row[0] . "'>"; // Utiliser l'ID de la ligne pour la suppression dynamique
        echo "<td>" . $row[0] . "</td>"; // Affiche l'ID
        echo "<td>" . $row[1] . "</td>"; // Affiche le nom d'utilisateur
        echo "<td>
          <button type='button' class='btn btn-secondary' onClick='updateRow(" . $row[0] . ", \"" . $row[1] . "\", \"update\")'>Update</button>
          <button class='btn btn-danger' type='button' onClick='updateRow(" . $row[0] . ", \"" . $row[1] . "\", \"delete\")'>Delete</button>
        </td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>
<?php
} else {
  echo "Aucun utilisateur trouvé dans la base de données.";
}

$conn->close();
?>

<div class="container">
  <form class="d-none" id="updateForm" action="front2.php" method="post" style="width: 350px;">
    <input id="id_user" type="hidden" class="form-control" name="id">
    <input id="btnAction" type="hidden" class="form-control" name="btnAction">

    <div class="mb-3">
      <label for="username" class="form-label">Nom d'utilisateur</label>
      <input type="text" class="form-control" id="username" name="username">
      <label for="password" class="form-label">Mot de passe</label>
      <input type="password" class="form-control" id="password" name="password">
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
  </form>
</div>
<form id="deleteForm" action="front2.php" method="POST" class="d-none">
  <input type="hidden" name="id" id="delete_id">
  <input type="hidden" name="btnAction" value="delete">
</form>


<script type="text/javascript">
  /*function updateRow(id, username, action) {
    // Afficher le formulaire pour l'action "update"
    document.getElementById('updateForm').classList.remove('d-none');
    document.getElementById('updateForm').classList.add('d-block');
    document.getElementById('id_user').value = id;
    document.getElementById('username').value = username;
    document.getElementById('btnAction').value = action;

    // Si l'action est "delete", soumettre le formulaire et supprimer dynamiquement la ligne
    if (action === "delete") {
      // Soumettre le formulaire de suppression sans afficher les champs
      document.getElementById('updateForm').submit(); // Soumettre le formulaire
      // Supprimer dynamiquement la ligne du tableau
      var row = document.getElementById("userRow" + id);
      row.remove(); // Utiliser remove() pour supprimer correctement la ligne
    } 
    // Pour l'action "update"
    else if (action === "update") {
      document.getElementById("updateForm").querySelector("button").innerText = "Enregistrer les modifications";
    }*/

function updateRow(id, username, action) {
  if (action === "delete") {
    document.getElementById("delete_id").value = id;
    document.getElementById("deleteForm").submit();
  } else if (action === "update") {
    const form = document.getElementById('updateForm');
    form.classList.remove('d-none');
    form.classList.add('d-block');
    document.getElementById('id_user').value = id;
    document.getElementById('username').value = username;
    document.getElementById('btnAction').value = action;
    form.querySelector("button").innerText = "Enregistrer les modifications";
  }
}

</script>

<?php
/*Sanitisation*/
$options = array(
  'tache'         => FILTER_SANITIZE_STRING,
  'tache_ligne'   => FILTER_SANITIZE_STRING
);
$result = filter_input_array(INPUT_POST, $options);
$_POST["tache"] = filter_var($_POST["tache"], FILTER_SANITIZE_STRING);
$_POST["tache_ligne"] = filter_var($_POST["tache_ligne"], FILTER_SANITIZE_STRING);
$_POST["ajouter"] = filter_var($_POST["ajouter"], FILTER_SANITIZE_STRING);
$_POST["submit"] = filter_var($_POST["submit"], FILTER_SANITIZE_STRING);

/*fin Sanitisation*/
//Requête POST:
//vérification des valeurs après la Sanitisation
if($result != null && $result != FALSE && $_SERVER['REQUEST_METHOD']=='POST')
{
  /*vérifie si on a cliqué sur le bouton "ajouter".*/
  if(isset($_POST["ajouter"]) && $_POST["ajouter"] == "Ajouter"){
  $tache=$_POST["tache"];
    if (!empty($tache)) {

  ecrireJSON($tache, false);
  }
  }
  if(isset($_POST["submit"]) && $_POST["submit"] == "Enregistrer")
  {
    /*$tache_ligne est un tableau*/
    $tache_ligne = $_POST["tache_ligne"];
    /*boucle sur le tableau $tache_ligne*/
    for($i = 0; $i < sizeof($tache_ligne); $i++)
      enregistreJSON($tache_ligne[$i]);
  }
}
/*fonction qui transforme les variable en format JSON*/
function ecrireJSON($tache, $terminer)
{
  /*appel de la fonction "tableauJSON", $tabjson reçoit un tableau d'objet JSON*/
  $tabjson = tableauJSON();
  /*CREATION JSON*/
  /*Création d'une table ($tab) qui deviendra un objet JSON*/
  $tab = array("Nom" => $tache, "Terminer" => $terminer );
  /*ajout de l'objet JSON dans  la table qui reçois les objets JSON*/
  $tabjson[] = $tab;
  /*utilise la fonction "sauvegardeJSON" en lui envoyant un tablreau d'objets JSON ($tabjson)*/
  sauvegardeJSON($tabjson);
}
 ?>

 <?php
   /*##################################################################*/
   /*fonction qui ouvre le fichier (todo.json) et le transforme en tableau d'objets JSON*/
   /*retourne le tableau d'objets JSON*/
   function tableauJSON()
   {
     /*nom du fichier*/
     $filename = "./todo.json";
     /*récupère la totalité du fichier (todo.json) sous forme de chaîne caractère*/
     $file = file_get_contents($filename);
     /*crée une variable table qui va recevoir les objets JSON*/
     $tabjson;
     if(empty($file))  /*si le fichier est vide : crée une table*/
       $tabjson = json_decode("[]");
     else /*sinon : il decode la chaîne de caractère en objets JSON*/
       $tabjson = json_decode($file);
     return $tabjson;
   }
   /*##################################################################*/
   /*fonction qui sauvegarde un tableau d'objets JSON dans le fichie (todo.json)*/
   /*reçoit comme paramètre un tableau d'objets JSON ($tabjson)*/
   /*retourne false en cas d'erreur ou le nombre de caractères en cas de réussite*/
   function sauvegardeJSON($tabjson)
   {
     /*nom du fichier JSON*/
     $filename = "./todo.json";
     /*encode la table d'objets JSON en format chaîne de caractères*/
     $str_json = json_encode($tabjson, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
     /*inscript la chaîne de caractère dans le fichier (todo.json)*/
     $resultat = file_put_contents($filename, $str_json);
     /*retourne le résultat ($resultat)*/
     return $resultat;
   }
   /*##################################################################*/
   /*fonction qui affiche les Json en format HTML*/
   /*La fonction reçoit comme paramètre ($termin) une valeur de type booléan*/
   function afficheJSON($termin)
   {
     /*appel de la fonction "tableauJSON" $tabjson reçoit un tableau d'objet JSON*/
     $tabjson = tableauJSON();
     /*boucle qui parcours le tableau JSON et crée les balises label et checkbox*/
     for($i=0; $i < sizeof($tabjson); $i++)
     {
       /*$obj reçoit l'objet JSON*/
       $obj = $tabjson[$i];
       /*Condition sur la variable "Terminer" de l'objet Json*/
       if($obj->Terminer == $termin)
       {
         $txt = '<div class="draggable"';
         /*balise ouvrante <label>*/
         $txt .= '<label class="';
         $txt .= $termin?"tache_terminer":"tache_non_terminer";
         $txt .= '" for="">';
         /*début : balise <input>*/
         $txt .= '<input type="checkbox" name="tache_ligne[]" value="';
         /*$i représente le numero de la ligne*/
         $txt .= $i.'" ';
         /*si la valeur $termin est vraie ajouter l'attribut "checked" */
         $txt .= $termin?"checked":"";
         $txt .= " onfocus='focuscheck($i);' ";
         $txt .= ">";
         /*fin : balise <input>*/
         /*balise fermante <label>*/
         $txt .= $obj->Nom.'</label>';
         $txt .= "<br/>";
         $txt .= '</div>';
         echo $txt;
       }
     }
   }
   /*##################################################################*/
   /*fonction qui modifie l'objet JSON*/
   /*la fonction reçoit comme paramètre l'index de l'objet qui doit être modifier ($index)*/
   function enregistreJSON($index)
   {
     /*appel de la fonction "tableauJSON" $tabjson reçoit un tableau d'objet JSON*/
     $tabjson = tableauJSON();
     /*casting de la variable $index en INT*/
     $index = (int)$index;
     /*place l'objet JSON à l'index ($index) du tableau ($tabjson) dans la variable ($obj)*/
     $obj = $tabjson[$index];
     /*modifie la valeur "Terminer" de l'objet JSON $obj en son inverse (true <-> false)*/
     $obj->Terminer = !$obj->Terminer;
     /*utilise la fonction "sauvegardeJSON" en lui envoyant un tablreau d'objets JSON ($tabjson)*/
     sauvegardeJSON($tabjson);
   }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["tache"])) {
  $error = "N'oubliez pas la tâche !";
  } else {
    $error = "Ajoutée ;-) ";
  }
}

  ?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="style.css" rel="stylesheet">
  <title>My To-do List</title>
</head>

<body>
  <div class="contenu">
    <header>
      <img class="displayed" src="logo2.png" alt="My To Do List">
    </header>

    <section>
      <fieldset class="afaire">
        <legend>
          <h2>A faire</h2></legend>
        <form action="index.php" method="POST">

          <div class="dropper">
            <?php
            afficheJSON(false);
            ?>
          </div>
          <input class="bouton-none" type="submit" name="submit" value="Enregistrer">
        </form>
      </fieldset>
      <fieldset class="archives">
        <legend>
          <h2>Archives</h2></legend>

        <div class="dropper">
          <span> <?php
        afficheJSON(true);
      ?></span>
        </div>
      </fieldset>
    </section>

    <section>

      <fieldset class="tache">

        <legend>
          <h2>Nouvelle tâche : </h2></legend>

        <form action="index.php" method="POST">
          <textarea name="tache" value="" placeholder="Écrire ici la nouvelle tâche"></textarea><br>
          <div class="erreur">


            <?php echo $error; ?>
          </div>
          <br>
          <input class="bouton" type="submit" name="ajouter" value="Ajouter">

      </fieldset>
      </form>

    </section>
  </div>
</body>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
<script>

(function(){
  var dndHandler = {
    draggedElement: null,
    applyDragEvents: function(element) {
      element.draggable = true;
      var dndHandler = this;
      element.addEventListener('dragstart', function(e) {
        dndHandler.draggedElement = e.target;
        e.dataTransfer.setData('text/plain', '');
      }, false);
  },
    applyDropEvents: function(dropper){
      dropper.addEventListener('dragover', function(e){
        e.preventDefault();
        this.className = 'dropper drop_hover';
      }, false);
      dropper.addEventListener('dragleave', function(){
        this.className = 'dropper';
      });
      var dndHandler = this;
      dropper.addEventListener('drop', function(e){
        var target = e.target,
        draggedElement = dndHandler.draggedElement,
        clonedElement = draggedElement.cloneNode(true);
        while(target.className.indexOf('dropper') == -1){
          target = target.parentNode;
        }
        target.className = 'dropper';
        clonedElement = target.appendChild(clonedElement);
        dndHandler.applyDragEvents(clonedElement);
        draggedElement.parentNode.removeChild(draggedElement);
      });
     }
};
  var elements = document.querySelectorAll('.draggable'),
  elementsLen = elements.length;
  for(var i = 0 ; i < elementsLen ; i++){
    dndHandler.applyDragEvents(elements[i]);
  }
  var droppers = document.querySelectorAll('.dropper'),
  droppersLen = droppers.length;
  for(var i = 0 ; i < droppersLen ; i++) {
    dndHandler.applyDropEvents(droppers[i]);
  }
})();
function focuscheck(index)
{
  $.post(
    'index.php',
    {
      "tache_ligne":index,
      "submit":"Enregistrer"
    },
    function(data)
    {
      document.location.href="index.php";
    },
    'text'
  );
};
    </script>


</html>

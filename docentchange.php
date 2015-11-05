<?php
require_once($BASEDIR . 'config.php');
include 'header/header.php';

$conn = new mysqli($server, $username, $password, $database);

if($conn->connect_errno) {
    die('Could not connect: ' .$mysqli->connect_error);
}

?>

<?php 
$opdrid = '6';
$sql = "SELECT * FROM opdrachten WHERE id = '{$opdrid}'";
$currentValues = $conn->query($sql);
echo 'De huidige waardes van deze opdracht zijn, klik op Save om deze te wijzigen'.'<br />';
if($currentValues->num_rows > 0){
	while($row = $currentValues->fetch_assoc()){
		$name = $row["naam"];
		$description = $row["description"];
		$level = $row["moeilijkheidsgraad"];
		$category = $row["categorie"];
		$requirements = $row["requirements"];
		$template = $row["templatecode"];
	}
} else {
	echo 'Deze opdracht-id is (nog) niet opgeslagen!';
}
?>

<?php
	if(isset($_POST['submitAll'])) {
		if(isset($_POST['name'])){
			$name = $_POST['name'];
			$sql = "UPDATE opdrachten SET naam = '{$name}' WHERE id = '{$opdrid}'";
			$conn->query($sql); //update the database
		}
		if(isset($_POST['description'])) {
			$description = $_POST['description'];
			$sql = "UPDATE opdrachten SET description = '{$description}' WHERE id = '{$opdrid}'";
			$conn->query($sql); //update the database
		}
		if(isset($_POST['level'])) {
			$level = $_POST['level'];
			$sql = "UPDATE opdrachten SET moeilijkheidsgraad = '{$level}' WHERE id = '{$opdrid}'";
			$conn->query($sql); //update the database
		}
		if(isset($_POST['category'])) {
			$category = $_POST['category'];
			$sql = "UPDATE opdrachten SET categorie = '{$category}' WHERE id = '{$opdrid}'";
			$conn->query($sql); //update the database
		}
		if(isset($_POST['requirements'])) {
			$requirements = $_POST['requirements'];
			$sql = "UPDATE opdrachten SET requirements = '{$requirements}' WHERE id = '{$opdrid}'";
			$conn->query($sql); //update the database
		}
		if(isset($_POST['template'])) {
			$template = $_POST['template'];
			$sql = "UPDATE opdrachten SET templatecode = '{$template}' WHERE id = '{$opdrid}'";
			$conn->query($sql); //update the database
		}
	}
mysqli_close($conn);
?>

<form action="docentchange.php" name="formvalues" method="post">
	Naam:
	<input type="text" name="name" value="<?php echo $name; ?>"/>	
	<br />
	Ondertitel:
	<input type="text" name="description" value="<?php echo $description; ?>"/>	
	<br />
	Moeilijkheidsgraad:
	<input type="number" name="level" value="<?php echo $level; ?>"/>
	<br />
	Categorie:
	<input type="text" name="category" value="<?php echo $category; ?>"/>
	<br />
	Requirements:
	<input type="text" name="requirements" value="<?php echo $requirements; ?>"/>
	<br />
	Template code:
	<textarea rows="10" cols="40" name="template" id="textarea" class="codetextarea"><?php echo $template; ?></textarea>
	<br />
	<input type="submit" name="submitAll" value="Save" />
</form>
<script>
                document.querySelector("textarea").addEventListener
                ('keypress',function(keystroke) {//ignores special keys like shift and tab (eg shift + ] is }, but } will then not be recognised)
                    if (keystroke.keyCode === 13) {//The keystroke was an [enter]
                        var start = this.selectionStart;
                        var target = keystroke.target;
                        var value = target.value;
                        var newline ="";
                                                                    
                        var brackets = 0;
                        //Goes back to the beginning of the line and checks for tabs
                        for (var i = textarea.selectionStart-1; i >= 0 ; i--) {
                            if (value[i] == "\t") {//Tabs for each tab at the beginning of the previous line
                                newline = newline + "\t";
                            } else if (value[i] == "\n") {//Beginning of the line
                                break;
                            } else if (value[i] == "{") {
                                brackets ++;
                            } else if (value[i] == "}" && brackets <= 0) {//No additional tab if as many brackets on this line were closed as opened
                                brackets --;
                            } else {//Only add tabs if there's no other characters before them
                                newline = "";
                            }
                        }
                        if (brackets > 0)//add one additional tab
                            newline += "\t";
                        
                        //The textarea value becomes: text before cursor + [enter] + an amount of tabs + text after cursor
                        target.value = value.substring(0, start) + "\n" + newline + value.substring(this.selectionEnd);
                        //put cursor after the old position + 1 enter + all tabs.
                        this.selectionStart = this.selectionEnd = start + newline.length + 1;
                        keystroke.preventDefault();
                        
                    } else if (keystroke.keyCode === 125) {// "}"
                 
                        var start = this.selectionStart;
                        var target = keystroke.target;
                        var value = target.value;

                        //Goes back to the beginning of the line and checks for tabs
                        for (var i = textarea.selectionStart-1; i >= 0 ; i--) {
                            if (value[i] == "\t") {//Tabs for each tab at the beginning of the previous line
                                newline = newline + "\t";
                            } else if (value[i] == "\n") {//Beginning of the line
                                break;
                            } else {//another character found: do not jump one tab back
                                target.value = value.substring(0, start) + "}" + value.substring(this.selectionEnd);//add "}"
                                this.selectionStart = this.selectionEnd = start + 1;
                                keystroke.preventDefault();
                                return;
                            }
                        }
                        if (start == 0) {
                            target.value = "}" + value.substring(this.selectionEnd);
                            this.selectionStart = this.selectionEnd = start + 1;
                        } else {
                            //The textarea value becomes: text before cursor + 1 tab less + } + text after cursor
                            target.value = value.substring(0, start-1) + "}" + value.substring(this.selectionEnd);
                            //put cursor after the old position (-1 +1 character is no displacement).
                            this.selectionStart = this.selectionEnd = start;
                        }
                        keystroke.preventDefault();
                    }
                },false);
                document.querySelector("textarea").addEventListener('keydown',function(keystroke) {//keydown can detect tabs
                     //Prevents an element outside of the textbox being selected whenever you press tab.
                        if (keystroke.keyCode === 9) {//The keystroke was a tab
                     
                            var start = this.selectionStart;
                            var target = keystroke.target;
                            var value = target.value;
                     
                            //The textarea value becomes: text before cursor + tab + text after cursor
                            target.value = value.substring(0, start) + "\t" + value.substring(this.selectionEnd);
                            //put cursor at one after the old position (because of the added tab)
                            this.selectionStart = this.selectionEnd = start + 1;
                            keystroke.preventDefault();
                        }
                     },false);
</script>
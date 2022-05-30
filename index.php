<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form action="index.php" method="post" enctype="multipart/form-data">
        <!-- file upload form -->
        <strong>Select a txt file to upload:</strong>
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Carica file" name="submit">

            <?php

                $target_dir = "/";
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                // $uploadOk = 1;
                $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                
                // Allow certain file formats
                if( $_FILES["fileToUpload"]["type"] != "text/plain" ) {
                    echo "Sorry, only txt files are allowed.";
                    // die();
                    // $uploadOk = 0;
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
                } else {
                    $temp = explode(".", $_FILES["fileToUpload"]["name"]);
                    $newfilename = "data" . '.' . end($temp);
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $newfilename)) {
                        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                        // die();
                    }
                }
            ?>

    </form>
    <h1>File di testo:</h1>
    <p> 

    <!-- Lorem Ipsum generator -> https://jaspervdj.be/lorem-markdownum/markdown.txt -->

        <?php
            // print the file checking for html code
            foreach (file('data.txt') as $line) {
                echo htmlentities($line) . "<br>";
            }

        ?>
    </p>

    <?php

        $words = [];

        foreach (file('data.txt') as $line) {

            // i remove non alphabetic characters
            $cleanLine = preg_replace("/[^A-Za-z ]/", '', $line);
            
            // i separate the words
            $lineWords = explode(" ", $cleanLine);

            foreach ($lineWords as $word) {
                // push every word in lowercase for better comparison
                $words[] = strtolower($word);
            }
        }

        $sameWords = [];

        foreach($words as $w1) {
            
            // i consider only words from 2 character minimum
            if (strlen($w1) > 1) {

                // n. of times the same element appear in the array
                $count = count(array_keys($words, $w1));
                if ($count > 1) {

                    $recurring["word"] = $w1;
                    $recurring["count"] = $count;

                    // check if it's already in the array
                    if (array_search($recurring, $sameWords) === false) {

                        $sameWords[] = $recurring;
                    }
                }
            }
        }

        // print the count of the recurring words
        echo "<h2>Parole ricorrenti (" . count($sameWords) . ") :</h2>";
    ?>
    <ul>
        <?php
        
            // sorting the array in descenting order
            usort($sameWords,function($first,$second){
                return $first["count"] < $second["count"];
            });

            // print each word with its count
            foreach($sameWords as $word) {

                echo "<li>" . $word["word"] . " [ x " . $word["count"] . "]</li>";
            }
        ?>
    </ul>
</body>
</html>
<?php
// Sam Duncan
// cosc2328
// October 7st, 2014

require "../phpStuff/classfun.php";
printDocHeading("../styleSheets/mad-style.css", "Mad-Lib Stories");
print "<body>\n<div class='content'>\n";
print "<h2>Mad-Lib Stories</h2>\n";
//printAll();
if(empty ($_POST))
{
  showStoryChoiceForm();
}
else if ($_POST['submitStoryChoice'])
{
  checkStorySubmission();
}
else if ($_POST['subStory'])
{
  showSubChoiceForm();
}
else if ($_POST['submitSubChoice'])
{
  checkSubSubmission();
}

print "</div>\n";  // end of opening div
printDocFooter();


//function displays a drop down menu that allows user to choose between
//three options for a story to mad-lib.
function showStoryChoiceForm()
{
	$self = $_SERVER['PHP_SELF'];
	print "<div><form method = 'post' action = '$self'>\n";
	print "<select name = 'storyNum'>\n";
	print "<option value = '0'> choose story from list </option>\n";
	print "<option value = '1'> A betrayal! </option>\n";
	print "<option value = '2'> An addiction! </option>\n";
	print "<option value = '3'> A tragedy! </option>\n";
	print "</select>\n";
	print "<input type = 'submit' name = 'submitStoryChoice' value = 'Submit Story'/>\n";
	print "</form>\n</div>\n";
}


//function that displays the chosen story and its substituted version
//also checks to ensure that valid choice was made.
function checkStorySubmission()
{
	$self = $_SERVER['PHP_SELF'];
	$num = htmlentities($_POST['storyNum'], ENT_QUOTES);
	if ($num < 1 || $num > 3)
	{
		showStoryChoiceForm();
		return;
	}
	$filename = "story".$num.".txt";
	$contents = file_get_contents($filename);
	$contents = nl2br($contents);
	print "<h3> Here is the original story: </h3>\n";
	print $contents;
	print "<div><form method = 'post' action = '$self'>\n";
	print "<input type = 'submit' name = 'subStory' value = 'Substitute Story'/>\n";
        print "<input type='hidden' name='storyNum' value='$num' />\n";
	print "</form>\n</div>\n";
//	startOverLink();
}

//Function that allows user to input substitutions into the story based on
//the patterns taken from matches
function showSubChoiceForm($subs = array())
{
	$self = $_SERVER['PHP_SELF'];
	$num = htmlentities($_POST['storyNum'], ENT_QUOTES);
	$filesub = "story".$num."Sub.txt";
	$subcontents = file_get_contents($filesub);
	$subcontens = nl2br($subcontents);
	$pattern = "/\[(.+?)\]/";
	$matches = array();
	preg_match_all($pattern, $subcontents, $matches);

	print "<div><form method = 'post' action = '$self'>\n";
	print "<h5> Please enter your substitutions: </h5>";
	for($i = 0; $i < count($matches[0]); $i++)
	{
		print "<input type = 'text' name='subs[]' value = '$subs[$i]'/>".":".$matches[1][$i]."<br />";
	}

	print "<input type = 'hidden' name = 'storyNum' value = '$num'/>\n";
	print "<input type = 'submit' name = 'submitSubChoice' value = 'Submit'/>\n";
	print "</form>\n</div>\n";
}

//Function that displays the substituted story with
//All the substitutions made by the user
function checkSubSubmission()
{
	$self = $_SERVER['PHP_SELF'];
	$num = htmlentities($_POST['storyNum'], ENT_QUOTES);
	if ($num < 1 || $num > 3)
	{
		showStoryChoiceForm();
		return;
	}
	$filename = "story".$num."Sub.txt";
	$subcontents = file_get_contents($filename);
	$subcontents = nl2br($subcontents);

	$pattern = "/\[(.+?)\]/";
	$matches = array();
	preg_match_all($pattern, $subcontents, $matches);
	$subs = $_POST['subs'];

//check if subs is empty, return to substitution form if so
	$error = "";
	for($i = 0; $i < count($subs); $i++)
	{
		if($subs[$i] == "")
		{
			$error .= "<br />Nothing entered. Please reenter input.";
		}
	}
	if ($error)
	{
		print $error;
		showSubChoiceForm($subs);
	}

	else
	{
		for($i = 0; $i < count($subs); $i++)
		{
			$subcontents = str_replace($matches[0][$i], $subs[$i], $subcontents);
			}
			print "<h3> Here is the substituted story: </h3>\n";
			print $subcontents;
			startOverLink();
	}
}
?>

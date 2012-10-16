<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/redmond/jquery-ui.css">
  
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
 
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>

<script>

$(function() {
         

            
            $("#fullname").autocomplete({
                source: "http://online.ran.org/api/reports/ajax_supporter.php",
                minLength: 2,
                
            select: function(event, ui) {
                    $('#supporter_KEY').val(ui.item.id);
                }    
            });

        });



</script>
<form action="<?php echo $PHP_SELF;?>"  method="post">
<fieldset>
<legend>jQuery UI Autocomplete Example - PHP Backend</legend>
 
<p>Start typing the name of a state or territory of the United States</p>
 
<p class="ui-widget">
 
<label for="fullname">State (abbreviation in separate field): </label>
 
<input type="text" id="fullname"  name="fullname" />
 
<input readonly="readonly" type="text" id="supporter_KEY" name="supporter_KEY" maxlength="20" size="20"/></p>
 
 
 
<input type="submit" name="submitBtn" value="Submit" /></p>
 
</fieldset>
</form>

<?php
if (isset($_POST['submit'])) {
echo "<p>";
    while (list($key,$value) = each($_POST)){
    echo "<strong>" . $key . "</strong> = ".$value."<br />";
    }
echo "</p>";
}
?>
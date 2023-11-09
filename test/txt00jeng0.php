<html>
<head>
<script src="../asset/jquery.min.js"></script>
<script src="../asset/jquery-ui-custom.js"></script>

</head>

<div><input type='radio' name='pop10' class='savedata' value="A" />A
<input type='radio' name='pop10' class='savedata' value="B" />B</div>
<div><input type='checkbox' name='pop09' class='chk-labtest-sel' id="CD4%" />
<div><input type='text' id="txtCD4%" value="xxx" />
<div data-name='pop11'><input name='pop11' value='Last Item' /></div>

 <script>

 $(document).ready(function(){
   xObj = $("input[name='pop11']");
   $(".savedata[name='pop10'][value='B']").after(xObj);
 });
 </script>
 </html>

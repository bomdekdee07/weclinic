<script>
var lab_id = "-2256";
console.log("check "+lab_id);
var first_str_id = lab_id.substring(0, 1);
if(!first_str_id.match(/^[A-Za-z]+$/)){
  console.log("not true");
}
else{
  console.log("true");
}

</script>

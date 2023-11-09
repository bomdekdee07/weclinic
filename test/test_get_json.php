
test
<br>

<script>


$.getJSON('http://192.168.108.11/weclinic/api/uic_center.php?id=1840100524738', function(data) {
    // JSON result in `data` variable
		console.log("citizen_id: "+data.citizen_id);
});
</script>

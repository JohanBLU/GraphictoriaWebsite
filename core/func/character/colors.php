<div class="modal fade" id="colordialog" tabindex="-1" role="dialog" aria-labelledby="colord">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="colord">Update Color</h4>
         </div>
         <div class="modal-body Center">
			<button type="submit" value="1" onclick="updateColor($(this).val());" style="background:#F2F3F3;height:32px;width:32px;"/>
			<button type="submit" value="208" onclick="updateColor($(this).val());" style="background:#E5E4DF;height:32px;width:32px;"/>
			<button type="submit" value="194" onclick="updateColor($(this).val());" style="background:#A3A2A5;height:32px;width:32px;"/>
			<button type="submit" value="199" onclick="updateColor($(this).val());" style="background:#635F62;height:32px;width:32px;"/>
			<button type="submit" value="26" onclick="updateColor($(this).val());" style="background:#1B2A35;height:32px;width:32px;"/>
			<button type="submit" value="21" onclick="updateColor($(this).val());" style="background:#C4281C;height:32px;width:32px;"/>
			<button type="submit" value="24" onclick="updateColor($(this).val());" style="background:#F5CD30;height:32px;width:32px;"/>
			<button type="submit" value="226" onclick="updateColor($(this).val());" style="background:#FDEA8D;height:32px;width:32px;"/>
			<button type="submit" value="23" onclick="updateColor($(this).val());" style="background:#0D69AC;height:32px;width:32px;"/>
			<button type="submit" value="107" onclick="updateColor($(this).val());" style="background:#008F9C;height:32px;width:32px;"/>
			<button type="submit" value="102" onclick="updateColor($(this).val());" style="background:#6E99CA;height:32px;width:32px;"/>
			<button type="submit" value="11" onclick="updateColor($(this).val());" style="background:#80BBDB;height:32px;width:32px;"/>
			<button type="submit" value="45" onclick="updateColor($(this).val());" style="background:#B4D2E4;height:32px;width:32px;"/>
			<button type="submit" value="135" onclick="updateColor($(this).val());" style="background:#74869D;height:32px;width:32px;"/>
			<button type="submit" value="105" onclick="updateColor($(this).val());" style="background:#E29B40;height:32px;width:32px;"/>
			<button type="submit" value="141" onclick="updateColor($(this).val());" style="background:#27462D;height:32px;width:32px;"/>
			<button type="submit" value="37" onclick="updateColor($(this).val());" style="background:#4B974B;height:32px;width:32px;"/>
			<button type="submit" value="119" onclick="updateColor($(this).val());" style="background:#A4BD47;height:32px;width:32px;"/>
			<button type="submit" value="29" onclick="updateColor($(this).val());" style="background:#A1C48C;height:32px;width:32px;"/>
			<button type="submit" value="151" onclick="updateColor($(this).val());" style="background:#789082;height:32px;width:32px;"/>
			<button type="submit" value="38" onclick="updateColor($(this).val());" style="background:#A05F35;height:32px;width:32px;"/>
			<button type="submit" value="192" onclick="updateColor($(this).val());" style="background:#694028;height:32px;width:32px;"/>
			<button type="submit" value="104" onclick="updateColor($(this).val());" style="background:#6B327C;height:32px;width:32px;"/>
			<button type="submit" value="9" onclick="updateColor($(this).val());" style="background:#E8BAC8;height:32px;width:32px;"/>
			<button type="submit" value="101" onclick="updateColor($(this).val());" style="background:#DA867A;height:32px;width:32px;"/>
			<button type="submit" value="5" onclick="updateColor($(this).val());" style="background:#D7C59A;height:32px;width:32px;"/>
			<button type="submit" value="153" onclick="updateColor($(this).val());" style="background:#957977;height:32px;width:32px;"/>
			<button type="submit" value="217" onclick="updateColor($(this).val());" style="background:#7C5C46;height:32px;width:32px;"/>
			<button type="submit" value="18" onclick="updateColor($(this).val());" style="background:#CC8E69;height:32px;width:32px;"/>
			<button type="submit" value="125" onclick="updateColor($(this).val());" style="background:#EAB892;height:32px;width:32px;"/>
         </div>
      </div>
   </div>
</div>
<script src="/core/func/character/js/colorJs.js"></script>
<script>
	$(document).ready(function(){
		$("#head").click(function() {
			bodyPart = "head";
		});
		$("#leftarm").click(function() {
			bodyPart = "leftarm";
		});
		$("#torso").click(function() {
			bodyPart = "torso";
		});
		$("#rightarm").click(function() {
			bodyPart = "rightarm";
		});
		$("#leftleg").click(function() {
			bodyPart = "leftleg";
		});
		$("#rightleg").click(function() {
			bodyPart = "rightleg";
		});
	});
</script>
<?php
	$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM characterColors WHERE uid=:id AND type='head';");
	$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
	$stmt->execute();
	$head = $stmt->fetch(PDO::FETCH_ASSOC);
	$headColor = true;
	if ($stmt->rowCount() == 0) {
		$headColor = false;
	}
	if ($headColor == false) {
		$head_hex = "#F2F3F3";
	}else{
		$h_color_int = $head['color'];
		if ($h_color_int == 1) {
			$head_hex = "#F2F3F3";
		}elseif ($h_color_int == 208) {
			$head_hex = "#E5E4DF";
		}elseif ($h_color_int == 194) {
			$head_hex = "#A3A2A5";
		}elseif ($h_color_int == 199) {
			$head_hex = "#635F62";
		}elseif ($h_color_int == 26) {
			$head_hex = "#1B2A35";
		}elseif ($h_color_int == 21) {
			$head_hex = "#C4281C";
		}elseif ($h_color_int == 24) {
			$head_hex = "#F5CD30";
		}elseif ($h_color_int == 226) {
			$head_hex = "#FDEA8D";
		}elseif ($h_color_int == 23) {
			$head_hex = "#0D69AC";
		}elseif ($h_color_int == 107) {
			$head_hex = "#008F9C";
		}elseif ($h_color_int == 102) {
			$head_hex = "#6E99CA";
		}elseif ($h_color_int == 11) {
			$head_hex = "#80BBDB";
		}elseif ($h_color_int == 45) {
			$head_hex = "#B4D2E4";
		}elseif ($h_color_int == 135) {
			$head_hex = "#74869D";
		}elseif ($h_color_int == 105) {
			$head_hex = "#E29B40";
		}elseif ($h_color_int == 141) {
			$head_hex = "#27462D";
		}elseif ($h_color_int == 37) {
			$head_hex = "#4B974B";
		}elseif ($h_color_int == 119) {
			$head_hex = "#A4BD47";
		}elseif ($h_color_int == 29) {
			$head_hex = "#A1C48C";
		}elseif ($h_color_int == 151) {
			$head_hex = "#789082";
		}elseif ($h_color_int == 38) {
			$head_hex = "#A05F35";
		}elseif ($h_color_int == 192) {
			$head_hex = "#694028";
		}elseif ($h_color_int == 104) {
			$head_hex = "#6B327C";
		}elseif ($h_color_int == 9) {
			$head_hex = "#E8BAC8";
		}elseif ($h_color_int == 101) {
			$head_hex = "#DA867A";
		}elseif ($h_color_int == 5) {
			$head_hex = "#D7C59A";
		}elseif ($h_color_int == 153) {
			$head_hex = "#957977";
		}elseif ($h_color_int == 217) {
			$head_hex = "#7C5C46";
		}elseif ($h_color_int == 18) {
			$head_hex = "#CC8E69";
		}elseif ($h_color_int == 125) {
			$head_hex = "#EAB892";
		}else{
			$head_hex = "#F2F3F3";
		}
	}
	
	$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM characterColors WHERE uid=:id AND type='torso';");
	$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
	$stmt->execute();
	$torso = $stmt->fetch(PDO::FETCH_ASSOC);
	$torsoColor = true;
	if ($stmt->rowCount() == 0) {
		$torsoColor = false;
	}
	if ($torsoColor == false) {
		$torso_hex = "#1B2A35";
	}else{
		$t_color_int = $torso['color'];
		if ($t_color_int == 1) {
			$torso_hex = "#F2F3F3";
		}elseif ($t_color_int == 208) {
			$torso_hex = "#E5E4DF";
		}elseif ($t_color_int == 194) {
			$torso_hex = "#A3A2A5";
		}elseif ($t_color_int == 199) {
			$torso_hex = "#635F62";
		}elseif ($t_color_int == 26) {
			$torso_hex = "#1B2A35";
		}elseif ($t_color_int == 21) {
			$torso_hex = "#C4281C";
		}elseif ($t_color_int == 24) {
			$torso_hex = "#F5CD30";
		}elseif ($t_color_int == 226) {
			$torso_hex = "#FDEA8D";
		}elseif ($t_color_int == 23) {
			$torso_hex = "#0D69AC";
		}elseif ($t_color_int == 107) {
			$torso_hex = "#008F9C";
		}elseif ($t_color_int == 102) {
			$torso_hex = "#6E99CA";
		}elseif ($t_color_int == 11) {
			$torso_hex = "#80BBDB";
		}elseif ($t_color_int == 45) {
			$torso_hex = "#B4D2E4";
		}elseif ($t_color_int == 135) {
			$torso_hex = "#74869D";
		}elseif ($t_color_int == 105) {
			$torso_hex = "#E29B40";
		}elseif ($t_color_int == 141) {
			$torso_hex = "#27462D";
		}elseif ($t_color_int == 37) {
			$torso_hex = "#4B974B";
		}elseif ($t_color_int == 119) {
			$torso_hex = "#A4BD47";
		}elseif ($t_color_int == 29) {
			$torso_hex = "#A1C48C";
		}elseif ($t_color_int == 151) {
			$torso_hex = "#789082";
		}elseif ($t_color_int == 38) {
			$torso_hex = "#A05F35";
		}elseif ($t_color_int == 192) {
			$torso_hex = "#694028";
		}elseif ($t_color_int == 104) {
			$torso_hex = "#6B327C";
		}elseif ($t_color_int == 9) {
			$torso_hex = "#E8BAC8";
		}elseif ($t_color_int == 101) {
			$torso_hex = "#DA867A";
		}elseif ($t_color_int == 5) {
			$torso_hex = "#D7C59A";
		}elseif ($t_color_int == 153) {
			$torso_hex = "#957977";
		}elseif ($t_color_int == 217) {
			$torso_hex = "#7C5C46";
		}elseif ($t_color_int == 18) {
			$torso_hex = "#CC8E69";
		}elseif ($t_color_int == 125) {
			$torso_hex = "#EAB892";
		}else{
			$torso_hex = "#1B2A35";
		}
	}
	
	$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM characterColors WHERE uid=:id AND type='rightarm';");
	$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
	$stmt->execute();
	$rightarm = $stmt->fetch(PDO::FETCH_ASSOC);
	$rightarmColor = true;
	if ($stmt->rowCount() == 0) {
		$rightarmColor = false;
	}
	if ($rightarmColor == false) {
		$rarm_hex = "#F2F3F3";
	}else{
		$rarm_color_int = $rightarm['color'];
		if ($rarm_color_int == 1) {
			$rarm_hex = "#F2F3F3";
		}elseif ($rarm_color_int == 208) {
			$rarm_hex = "#E5E4DF";
		}elseif ($rarm_color_int == 194) {
			$rarm_hex = "#A3A2A5";
		}elseif ($rarm_color_int == 199) {
			$rarm_hex = "#635F62";
		}elseif ($rarm_color_int == 26) {
			$rarm_hex = "#1B2A35";
		}elseif ($rarm_color_int == 21) {
			$rarm_hex = "#C4281C";
		}elseif ($rarm_color_int == 24) {
			$rarm_hex = "#F5CD30";
		}elseif ($rarm_color_int == 226) {
			$rarm_hex = "#FDEA8D";
		}elseif ($rarm_color_int == 23) {
			$rarm_hex = "#0D69AC";
		}elseif ($rarm_color_int == 107) {
			$rarm_hex = "#008F9C";
		}elseif ($rarm_color_int == 102) {
			$rarm_hex = "#6E99CA";
		}elseif ($rarm_color_int == 11) {
			$rarm_hex = "#80BBDB";
		}elseif ($rarm_color_int == 45) {
			$rarm_hex = "#B4D2E4";
		}elseif ($rarm_color_int == 135) {
			$rarm_hex = "#74869D";
		}elseif ($rarm_color_int == 105) {
			$rarm_hex = "#E29B40";
		}elseif ($rarm_color_int == 141) {
			$rarm_hex = "#27462D";
		}elseif ($rarm_color_int == 37) {
			$rarm_hex = "#4B974B";
		}elseif ($rarm_color_int == 119) {
			$rarm_hex = "#A4BD47";
		}elseif ($rarm_color_int == 29) {
			$rarm_hex = "#A1C48C";
		}elseif ($rarm_color_int == 151) {
			$rarm_hex = "#789082";
		}elseif ($rarm_color_int == 38) {
			$rarm_hex = "#A05F35";
		}elseif ($rarm_color_int == 192) {
			$rarm_hex = "#694028";
		}elseif ($rarm_color_int == 104) {
			$rarm_hex = "#6B327C";
		}elseif ($rarm_color_int == 9) {
			$rarm_hex = "#E8BAC8";
		}elseif ($rarm_color_int == 101) {
			$rarm_hex = "#DA867A";
		}elseif ($rarm_color_int == 5) {
			$rarm_hex = "#D7C59A";
		}elseif ($rarm_color_int == 153) {
			$rarm_hex = "#957977";
		}elseif ($rarm_color_int == 217) {
			$rarm_hex = "#7C5C46";
		}elseif ($rarm_color_int == 18) {
			$rarm_hex = "#CC8E69";
		}elseif ($rarm_color_int == 125) {
			$rarm_hex = "#EAB892";
		}else{
			$rarm_hex = "#F2F3F3";
		}
	}
	
	$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM characterColors WHERE uid=:id AND type='rightleg';");
	$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
	$stmt->execute();
	$rightleg = $stmt->fetch(PDO::FETCH_ASSOC);
	$rightlegColor = true;
	if ($stmt->rowCount() == 0) {
		$rightlegColor = false;
	}
	if ($rightlegColor == false) {
		$rl_hex = "#C4281C";
	}else{
		$rl_color_int = $rightleg['color'];
		if ($rl_color_int == 1) {
			$rl_hex = "#F2F3F3";
		}elseif ($rl_color_int == 208) {
			$rl_hex = "#E5E4DF";
		}elseif ($rl_color_int == 194) {
			$rl_hex = "#A3A2A5";
		}elseif ($rl_color_int == 199) {
			$rl_hex = "#635F62";
		}elseif ($rl_color_int == 26) {
			$rl_hex = "#1B2A35";
		}elseif ($rl_color_int == 21) {
			$rl_hex = "#C4281C";
		}elseif ($rl_color_int == 24) {
			$rl_hex = "#F5CD30";
		}elseif ($rl_color_int == 226) {
			$rl_hex = "#FDEA8D";
		}elseif ($rl_color_int == 23) {
			$rl_hex = "#0D69AC";
		}elseif ($rl_color_int == 107) {
			$rl_hex = "#008F9C";
		}elseif ($rl_color_int == 102) {
			$rl_hex = "#6E99CA";
		}elseif ($rl_color_int == 11) {
			$rl_hex = "#80BBDB";
		}elseif ($rl_color_int == 45) {
			$rl_hex = "#B4D2E4";
		}elseif ($rl_color_int == 135) {
			$rl_hex = "#74869D";
		}elseif ($rl_color_int == 105) {
			$rl_hex = "#E29B40";
		}elseif ($rl_color_int == 141) {
			$rl_hex = "#27462D";
		}elseif ($rl_color_int == 37) {
			$rl_hex = "#4B974B";
		}elseif ($rl_color_int == 119) {
			$rl_hex = "#A4BD47";
		}elseif ($rl_color_int == 29) {
			$rl_hex = "#A1C48C";
		}elseif ($rl_color_int == 151) {
			$rl_hex = "#789082";
		}elseif ($rl_color_int == 38) {
			$rl_hex = "#A05F35";
		}elseif ($rl_color_int == 192) {
			$rl_hex = "#694028";
		}elseif ($rl_color_int == 104) {
			$rl_hex = "#6B327C";
		}elseif ($rl_color_int == 9) {
			$rl_hex = "#E8BAC8";
		}elseif ($rl_color_int == 101) {
			$rl_hex = "#DA867A";
		}elseif ($rl_color_int == 5) {
			$rl_hex = "#D7C59A";
		}elseif ($rl_color_int == 153) {
			$rl_hex = "#957977";
		}elseif ($rl_color_int == 217) {
			$rl_hex = "#7C5C46";
		}elseif ($rl_color_int == 18) {
			$rl_hex = "#CC8E69";
		}elseif ($rl_color_int == 125) {
			$rl_hex = "#EAB892";
		}else{
			$rl_hex = "#C4281C";
		}
	}
	
	$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM characterColors WHERE uid=:id AND type='leftarm';");
	$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
	$stmt->execute();
	$leftarm = $stmt->fetch(PDO::FETCH_ASSOC);
	$leftarmColor = true;
	if ($stmt->rowCount() == 0) {
		$leftarmColor = false;
	}
	if ($leftarmColor == false) {
		$la_hex = "#F2F3F3";
	}else{
		$la_color_int = $leftarm['color'];
		if ($la_color_int == 1) {
			$la_hex = "#F2F3F3";
		}elseif ($la_color_int == 208) {
			$la_hex = "#E5E4DF";
		}elseif ($la_color_int == 194) {
			$la_hex = "#A3A2A5";
		}elseif ($la_color_int == 199) {
			$la_hex = "#635F62";
		}elseif ($la_color_int == 26) {
			$la_hex = "#1B2A35";
		}elseif ($la_color_int == 21) {
			$la_hex = "#C4281C";
		}elseif ($la_color_int == 24) {
			$la_hex = "#F5CD30";
		}elseif ($la_color_int == 226) {
			$la_hex = "#FDEA8D";
		}elseif ($la_color_int == 23) {
			$la_hex = "#0D69AC";
		}elseif ($la_color_int == 107) {
			$la_hex = "#008F9C";
		}elseif ($la_color_int == 102) {
			$la_hex = "#6E99CA";
		}elseif ($la_color_int == 11) {
			$la_hex = "#80BBDB";
		}elseif ($la_color_int == 45) {
			$la_hex = "#B4D2E4";
		}elseif ($la_color_int == 135) {
			$la_hex = "#74869D";
		}elseif ($la_color_int == 105) {
			$la_hex = "#E29B40";
		}elseif ($la_color_int == 141) {
			$la_hex = "#27462D";
		}elseif ($la_color_int == 37) {
			$la_hex = "#4B974B";
		}elseif ($la_color_int == 119) {
			$la_hex = "#A4BD47";
		}elseif ($la_color_int == 29) {
			$la_hex = "#A1C48C";
		}elseif ($la_color_int == 151) {
			$la_hex = "#789082";
		}elseif ($la_color_int == 38) {
			$la_hex = "#A05F35";
		}elseif ($la_color_int == 192) {
			$la_hex = "#694028";
		}elseif ($la_color_int == 104) {
			$la_hex = "#6B327C";
		}elseif ($la_color_int == 9) {
			$la_hex = "#E8BAC8";
		}elseif ($la_color_int == 101) {
			$la_hex = "#DA867A";
		}elseif ($la_color_int == 5) {
			$la_hex = "#D7C59A";
		}elseif ($la_color_int == 153) {
			$la_hex = "#957977";
		}elseif ($la_color_int == 217) {
			$la_hex = "#7C5C46";
		}elseif ($la_color_int == 18) {
			$la_hex = "#CC8E69";
		}elseif ($la_color_int == 125) {
			$la_hex = "#EAB892";
		}else{
			$la_hex = "#F2F3F3";
		}
	}
	
	$stmt = $GLOBALS['dbcon']->prepare("SELECT * FROM characterColors WHERE uid=:id AND type='leftleg';");
	$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
	$stmt->execute();
	$leftleg = $stmt->fetch(PDO::FETCH_ASSOC);
	$leftlegColor = true;
	if ($stmt->rowCount() == 0) {
		$leftlegColor = false;
	}
	if ($leftlegColor == false) {
		$ll_hex = "#C4281C";
	}else{
		$ll_color_int = $leftleg['color'];
		if ($ll_color_int == 1) {
			$ll_hex = "#F2F3F3";
		}elseif ($ll_color_int == 208) {
			$ll_hex = "#E5E4DF";
		}elseif ($ll_color_int == 194) {
			$ll_hex = "#A3A2A5";
		}elseif ($ll_color_int == 199) {
			$ll_hex = "#635F62";
		}elseif ($ll_color_int == 26) {
			$ll_hex = "#1B2A35";
		}elseif ($ll_color_int == 21) {
			$ll_hex = "#C4281C";
		}elseif ($ll_color_int == 24) {
			$ll_hex = "#F5CD30";
		}elseif ($ll_color_int == 226) {
			$ll_hex = "#FDEA8D";
		}elseif ($ll_color_int == 23) {
			$ll_hex = "#0D69AC";
		}elseif ($ll_color_int == 107) {
			$ll_hex = "#008F9C";
		}elseif ($ll_color_int == 102) {
			$ll_hex = "#6E99CA";
		}elseif ($ll_color_int == 11) {
			$ll_hex = "#80BBDB";
		}elseif ($ll_color_int == 45) {
			$ll_hex = "#B4D2E4";
		}elseif ($ll_color_int == 135) {
			$ll_hex = "#74869D";
		}elseif ($ll_color_int == 105) {
			$ll_hex = "#E29B40";
		}elseif ($ll_color_int == 141) {
			$ll_hex = "#27462D";
		}elseif ($ll_color_int == 37) {
			$ll_hex = "#4B974B";
		}elseif ($ll_color_int == 119) {
			$ll_hex = "#A4BD47";
		}elseif ($ll_color_int == 29) {
			$ll_hex = "#A1C48C";
		}elseif ($ll_color_int == 151) {
			$ll_hex = "#789082";
		}elseif ($ll_color_int == 38) {
			$ll_hex = "#A05F35";
		}elseif ($ll_color_int == 192) {
			$ll_hex = "#694028";
		}elseif ($ll_color_int == 104) {
			$ll_hex = "#6B327C";
		}elseif ($ll_color_int == 9) {
			$ll_hex = "#E8BAC8";
		}elseif ($ll_color_int == 101) {
			$ll_hex = "#DA867A";
		}elseif ($ll_color_int == 5) {
			$ll_hex = "#D7C59A";
		}elseif ($ll_color_int == 153) {
			$ll_hex = "#957977";
		}elseif ($ll_color_int == 217) {
			$ll_hex = "#7C5C46";
		}elseif ($ll_color_int == 18) {
			$ll_hex = "#CC8E69";
		}elseif ($ll_color_int == 125) {
			$ll_hex = "#EAB892";
		}else{
			$ll_hex = "#C4281C";
		}
	}
?>
<div class="Center">
   <div id="result">
   </div>
   <div class="table-responsive">
      <div style="height:240px;width:194px;text-align:center;margin:0 auto;">
         <div style="position: relative; margin: 11px 4px; height: 1%;">
            <div style="position: absolute; left: 72px; top: 0px; cursor: pointer">
               <div data-toggle="modal" id="head" data-target="#colordialog" style="background: <?php echo $head_hex; ?>;height:44px;width:44px;"> </div>
            </div>
			<div style="position: absolute; left: 144px; top: 52px; cursor: pointer">
               <div data-toggle="modal" id="leftarm" data-target="#colordialog" style="background: <?php echo $la_hex; ?>;height:88px;width:40px;"> </div>
            </div>
            <div style="position: absolute; left: 48px; top: 52px; cursor: pointer">
               <div data-toggle="modal" id="torso" data-target="#colordialog" style="background: <?php echo $torso_hex; ?>;height:88px;width:88px;"> </div>
            </div>
			<div style="position: absolute; left: 0px; top: 52px; cursor: pointer">
               <div data-toggle="modal" id="rightarm" data-target="#colordialog" style="background:<?php echo $rarm_hex; ?>;height:88px;width:40px;"> </div>
            </div>
			<div style="position: absolute; left: 96px; top: 146px; cursor: pointer">
               <div data-toggle="modal" id="leftleg" data-target="#colordialog" style="background:<?php echo $ll_hex; ?>;height:88px;width:40px;"> </div>
            </div>
			<div style="position: absolute; left: 48px; top: 146px; cursor: pointer">
               <div data-toggle="modal" id="rightleg" data-target="#colordialog" style="background:<?php echo $rl_hex; ?>;height:88px;width:40px;"> </div>
            </div>
         </div>
      </div>
   </div>
</div>
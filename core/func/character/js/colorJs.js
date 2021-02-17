function updateColor(code) {
	$('#colordialog').modal('toggle');
	if (bodyPart == "head") {
			$.get("/core/func/character/postsColor.php?updateHead=" + code, function(data){
			if (code == 1) {
				$("#head").css('background', "#F2F3F3");
			}
			if (code == 208) {
				$("#head").css('background', "#E5E4DF");
			}
			if (code == 194) {
				$("#head").css('background', "#A3A2A5");
			}
			if (code == 199) {
				$("#head").css('background', "#635F62");
			}
			if (code == 26) {
				$("#head").css('background', "#1B2A35");
			}
			if (code == 21) {
				$("#head").css('background', "#C4281C");
			}
			if (code == 24) {
				$("#head").css('background', "#F5CD30");
			}
			if (code == 226) {
				$("#head").css('background', "#FDEA8D");
			}
			if (code == 23) {
				$("#head").css('background', "#0D69AC");
			}
			if (code == 107) {
				$("#head").css('background', "#008F9C");
			}
			if (code == 102) {
				$("#head").css('background', "#6E99CA");
			}
			if (code == 11) {
				$("#head").css('background', "#80BBDB");
			}
			if (code == 45) {
				$("#head").css('background', "#B4D2E4");
			}
			if (code == 135) {
				$("#head").css('background', "#74869D");
			}
			if (code == 105) {
				$("#head").css('background', "#E29B40");
			}
			if (code == 141) {
				$("#head").css('background', "#27462D");
			}
			if (code == 37) {
				$("#head").css('background', "#4B974B");
			}
			if (code == 119) {
				$("#head").css('background', "#A4BD47");
			}
			if (code == 29) {
				$("#head").css('background', "#A1C48C");
			}
			if (code == 151) {
				$("#head").css('background', "#789082");
			}
			if (code == 38) {
				$("#head").css('background', "#A05F35");
			}
			if (code == 192) {
				$("#head").css('background', "#694028");
			}
			if (code == 104) {
				$("#head").css('background', "#6B327C");
			}
			if (code == 9) {
				$("#head").css('background', "#E8BAC8");
			}
			if (code == 101) {
				$("#head").css('background', "#DA867A");
			}
			if (code == 5) {
				$("#head").css('background', "#D7C59A");
			}
			if (code == 153) {
				$("#head").css('background', "#957977");
			}
			if (code == 217) {
				$("#head").css('background', "#7C5C46");
			}
			if (code == 18) {
				$("#head").css('background', "#CC8E69");
			}
			if (code == 125) {
				$("#head").css('background', "#EAB892");
			}
		});
	}
	
	if (bodyPart == "leftarm") {
			$.get("/core/func/character/postsColor.php?updateLeftArm=" + code, function(data){
			if (code == 1) {
				$("#leftarm").css('background', "#F2F3F3");
			}
			if (code == 208) {
				$("#leftarm").css('background', "#E5E4DF");
			}
			if (code == 194) {
				$("#leftarm").css('background', "#A3A2A5");
			}
			if (code == 199) {
				$("#leftarm").css('background', "#A3A2A5");
			}
			if (code == 26) {
				$("#leftarm").css('background', "#1B2A35");
			}
			if (code == 21) {
				$("#leftarm").css('background', "#C4281C");
			}
			if (code == 24) {
				$("#leftarm").css('background', "#F5CD30");
			}
			if (code == 226) {
				$("#leftarm").css('background', "#FDEA8D");
			}
			if (code == 23) {
				$("#leftarm").css('background', "#0D69AC");
			}
			if (code == 107) {
				$("#leftarm").css('background', "#008F9C");
			}
			if (code == 102) {
				$("#leftarm").css('background', "#6E99CA");
			}
			if (code == 11) {
				$("#leftarm").css('background', "#80BBDB");
			}
			if (code == 45) {
				$("#leftarm").css('background', "#B4D2E4");
			}
			if (code == 135) {
				$("#leftarm").css('background', "#74869D");
			}
			if (code == 105) {
				$("#leftarm").css('background', "#E29B40");
			}
			if (code == 141) {
				$("#leftarm").css('background', "#27462D");
			}
			if (code == 37) {
				$("#leftarm").css('background', "#4B974B");
			}
			if (code == 119) {
				$("#leftarm").css('background', "#A4BD47");
			}
			if (code == 29) {
				$("#leftarm").css('background', "#A1C48C");
			}
			if (code == 151) {
				$("#leftarm").css('background', "#789082");
			}
			if (code == 38) {
				$("#leftarm").css('background', "#A05F35");
			}
			if (code == 192) {
				$("#leftarm").css('background', "#694028");
			}
			if (code == 104) {
				$("#leftarm").css('background', "#6B327C");
			}
			if (code == 9) {
				$("#leftarm").css('background', "#E8BAC8");
			}
			if (code == 101) {
				$("#leftarm").css('background', "#DA867A");
			}
			if (code == 5) {
				$("#leftarm").css('background', "#D7C59A");
			}
			if (code == 153) {
				$("#leftarm").css('background', "#957977");
			}
			if (code == 217) {
				$("#leftarm").css('background', "#7C5C46");
			}
			if (code == 18) {
				$("#leftarm").css('background', "#CC8E69");
			}
			if (code == 125) {
				$("#leftarm").css('background', "#EAB892");
			}
		});
	}
	
	if (bodyPart == "torso") {
			$.get("/core/func/character/postsColor.php?updateTorso=" + code, function(data){
			if (code == 1) {
				$("#torso").css('background', "#F2F3F3");
			}
			if (code == 208) {
				$("#torso").css('background', "#E5E4DF");
			}
			if (code == 194) {
				$("#torso").css('background', "#A3A2A5");
			}
			if (code == 199) {
				$("#torso").css('background', "#A3A2A5");
			}
			if (code == 26) {
				$("#torso").css('background', "#1B2A35");
			}
			if (code == 21) {
				$("#torso").css('background', "#C4281C");
			}
			if (code == 24) {
				$("#torso").css('background', "#F5CD30");
			}
			if (code == 226) {
				$("#torso").css('background', "#FDEA8D");
			}
			if (code == 23) {
				$("#torso").css('background', "#0D69AC");
			}
			if (code == 107) {
				$("#torso").css('background', "#008F9C");
			}
			if (code == 102) {
				$("#torso").css('background', "#6E99CA");
			}
			if (code == 11) {
				$("#torso").css('background', "#80BBDB");
			}
			if (code == 45) {
				$("#torso").css('background', "#B4D2E4");
			}
			if (code == 135) {
				$("#torso").css('background', "#74869D");
			}
			if (code == 105) {
				$("#torso").css('background', "#E29B40");
			}
			if (code == 141) {
				$("#torso").css('background', "#27462D");
			}
			if (code == 37) {
				$("#torso").css('background', "#4B974B");
			}
			if (code == 119) {
				$("#torso").css('background', "#A4BD47");
			}
			if (code == 29) {
				$("#torso").css('background', "#A1C48C");
			}
			if (code == 151) {
				$("#torso").css('background', "#789082");
			}
			if (code == 38) {
				$("#torso").css('background', "#A05F35");
			}
			if (code == 192) {
				$("#torso").css('background', "#694028");
			}
			if (code == 104) {
				$("#torso").css('background', "#6B327C");
			}
			if (code == 9) {
				$("#torso").css('background', "#E8BAC8");
			}
			if (code == 101) {
				$("#torso").css('background', "#DA867A");
			}
			if (code == 5) {
				$("#torso").css('background', "#D7C59A");
			}
			if (code == 153) {
				$("#torso").css('background', "#957977");
			}
			if (code == 217) {
				$("#torso").css('background', "#7C5C46");
			}
			if (code == 18) {
				$("#torso").css('background', "#CC8E69");
			}
			if (code == 125) {
				$("#torso").css('background', "#EAB892");
			}
		});
	}
	
	if (bodyPart == "rightarm") {
			$.get("/core/func/character/postsColor.php?updateRightArm=" + code, function(data){
			if (code == 1) {
				$("#rightarm").css('background', "#F2F3F3");
			}
			if (code == 208) {
				$("#rightarm").css('background', "#E5E4DF");
			}
			if (code == 194) {
				$("#rightarm").css('background', "#A3A2A5");
			}
			if (code == 199) {
				$("#rightarm").css('background', "#A3A2A5");
			}
			if (code == 26) {
				$("#rightarm").css('background', "#1B2A35");
			}
			if (code == 21) {
				$("#rightarm").css('background', "#C4281C");
			}
			if (code == 24) {
				$("#rightarm").css('background', "#F5CD30");
			}
			if (code == 226) {
				$("#rightarm").css('background', "#FDEA8D");
			}
			if (code == 23) {
				$("#rightarm").css('background', "#0D69AC");
			}
			if (code == 107) {
				$("#rightarm").css('background', "#008F9C");
			}
			if (code == 102) {
				$("#rightarm").css('background', "#6E99CA");
			}
			if (code == 11) {
				$("#rightarm").css('background', "#80BBDB");
			}
			if (code == 45) {
				$("#rightarm").css('background', "#B4D2E4");
			}
			if (code == 135) {
				$("#rightarm").css('background', "#74869D");
			}
			if (code == 105) {
				$("#rightarm").css('background', "#E29B40");
			}
			if (code == 141) {
				$("#rightarm").css('background', "#27462D");
			}
			if (code == 37) {
				$("#rightarm").css('background', "#4B974B");
			}
			if (code == 119) {
				$("#rightarm").css('background', "#A4BD47");
			}
			if (code == 29) {
				$("#rightarm").css('background', "#A1C48C");
			}
			if (code == 151) {
				$("#rightarm").css('background', "#789082");
			}
			if (code == 38) {
				$("#rightarm").css('background', "#A05F35");
			}
			if (code == 192) {
				$("#rightarm").css('background', "#694028");
			}
			if (code == 104) {
				$("#rightarm").css('background', "#6B327C");
			}
			if (code == 9) {
				$("#rightarm").css('background', "#E8BAC8");
			}
			if (code == 101) {
				$("#rightarm").css('background', "#DA867A");
			}
			if (code == 5) {
				$("#rightarm").css('background', "#D7C59A");
			}
			if (code == 153) {
				$("#rightarm").css('background', "#957977");
			}
			if (code == 217) {
				$("#rightarm").css('background', "#7C5C46");
			}
			if (code == 18) {
				$("#rightarm").css('background', "#CC8E69");
			}
			if (code == 125) {
				$("#rightarm").css('background', "#EAB892");
			}
		});
	}
	
	if (bodyPart == "leftleg") {
			$.get("/core/func/character/postsColor.php?updateLeftLeg=" + code, function(data){
			if (code == 1) {
				$("#leftleg").css('background', "#F2F3F3");
			}
			if (code == 208) {
				$("#leftleg").css('background', "#E5E4DF");
			}
			if (code == 194) {
				$("#leftleg").css('background', "#A3A2A5");
			}
			if (code == 199) {
				$("#leftleg").css('background', "#A3A2A5");
			}
			if (code == 26) {
				$("#leftleg").css('background', "#1B2A35");
			}
			if (code == 21) {
				$("#leftleg").css('background', "#C4281C");
			}
			if (code == 24) {
				$("#leftleg").css('background', "#F5CD30");
			}
			if (code == 226) {
				$("#leftleg").css('background', "#FDEA8D");
			}
			if (code == 23) {
				$("#leftleg").css('background', "#0D69AC");
			}
			if (code == 107) {
				$("#leftleg").css('background', "#008F9C");
			}
			if (code == 102) {
				$("#leftleg").css('background', "#6E99CA");
			}
			if (code == 11) {
				$("#leftleg").css('background', "#80BBDB");
			}
			if (code == 45) {
				$("#leftleg").css('background', "#B4D2E4");
			}
			if (code == 135) {
				$("#leftleg").css('background', "#74869D");
			}
			if (code == 105) {
				$("#leftleg").css('background', "#E29B40");
			}
			if (code == 141) {
				$("#leftleg").css('background', "#27462D");
			}
			if (code == 37) {
				$("#leftleg").css('background', "#4B974B");
			}
			if (code == 119) {
				$("#leftleg").css('background', "#A4BD47");
			}
			if (code == 29) {
				$("#leftleg").css('background', "#A1C48C");
			}
			if (code == 151) {
				$("#leftleg").css('background', "#789082");
			}
			if (code == 38) {
				$("#leftleg").css('background', "#A05F35");
			}
			if (code == 192) {
				$("#leftleg").css('background', "#694028");
			}
			if (code == 104) {
				$("#leftleg").css('background', "#6B327C");
			}
			if (code == 9) {
				$("#leftleg").css('background', "#E8BAC8");
			}
			if (code == 101) {
				$("#leftleg").css('background', "#DA867A");
			}
			if (code == 5) {
				$("#leftleg").css('background', "#D7C59A");
			}
			if (code == 153) {
				$("#leftleg").css('background', "#957977");
			}
			if (code == 217) {
				$("#leftleg").css('background', "#7C5C46");
			}
			if (code == 18) {
				$("#leftleg").css('background', "#CC8E69");
			}
			if (code == 125) {
				$("#leftleg").css('background', "#EAB892");
			}
		});
	}
	
	if (bodyPart == "rightleg") {
			$.get("/core/func/character/postsColor.php?updateRightLeg=" + code, function(data){
			if (code == 1) {
				$("#rightleg").css('background', "#F2F3F3");
			}
			if (code == 208) {
				$("#rightleg").css('background', "#E5E4DF");
			}
			if (code == 194) {
				$("#rightleg").css('background', "#A3A2A5");
			}
			if (code == 199) {
				$("#rightleg").css('background', "#A3A2A5");
			}
			if (code == 26) {
				$("#rightleg").css('background', "#1B2A35");
			}
			if (code == 21) {
				$("#rightleg").css('background', "#C4281C");
			}
			if (code == 24) {
				$("#rightleg").css('background', "#F5CD30");
			}
			if (code == 226) {
				$("#rightleg").css('background', "#FDEA8D");
			}
			if (code == 23) {
				$("#rightleg").css('background', "#0D69AC");
			}
			if (code == 107) {
				$("#rightleg").css('background', "#008F9C");
			}
			if (code == 102) {
				$("#rightleg").css('background', "#6E99CA");
			}
			if (code == 11) {
				$("#rightleg").css('background', "#80BBDB");
			}
			if (code == 45) {
				$("#rightleg").css('background', "#B4D2E4");
			}
			if (code == 135) {
				$("#rightleg").css('background', "#74869D");
			}
			if (code == 105) {
				$("#rightleg").css('background', "#E29B40");
			}
			if (code == 141) {
				$("#rightleg").css('background', "#27462D");
			}
			if (code == 37) {
				$("#rightleg").css('background', "#4B974B");
			}
			if (code == 119) {
				$("#rightleg").css('background', "#A4BD47");
			}
			if (code == 29) {
				$("#rightleg").css('background', "#A1C48C");
			}
			if (code == 151) {
				$("#rightleg").css('background', "#789082");
			}
			if (code == 38) {
				$("#rightleg").css('background', "#A05F35");
			}
			if (code == 192) {
				$("#rightleg").css('background', "#694028");
			}
			if (code == 104) {
				$("#rightleg").css('background', "#6B327C");
			}
			if (code == 9) {
				$("#rightleg").css('background', "#E8BAC8");
			}
			if (code == 101) {
				$("#rightleg").css('background', "#DA867A");
			}
			if (code == 5) {
				$("#rightleg").css('background', "#D7C59A");
			}
			if (code == 153) {
				$("#rightleg").css('background', "#957977");
			}
			if (code == 217) {
				$("#rightleg").css('background', "#7C5C46");
			}
			if (code == 18) {
				$("#rightleg").css('background', "#CC8E69");
			}
			if (code == 125) {
				$("#rightleg").css('background', "#EAB892");
			}
		});
	}
}
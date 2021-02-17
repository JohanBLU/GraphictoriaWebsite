function loadFriends(userID, page) {
	$("#friendsContainer").load("/core/func/api/friends/get/showFriends.php?userid=" + userID + "&page=" + page);
}
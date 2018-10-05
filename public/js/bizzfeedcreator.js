//Static variables
const handlerLink = BASEURI + "/bizzfeed/creator/action/";
const feedLink = BASEURI + "/js/bizzfeed.js";
//Configure some components
$("#titlebarColor").spectrum({
	color: "#00B3E3", allowEmpty: false, change: function(color) {
		updateColor(0, color);
	}, hide: function(color) {
		updateColor(0, color);
	}
});
$("#titleColor").spectrum({
	color: "#FFFFFF", allowEmpty: false, change: function(color) {
		updateColor(1, color);
	}, hide: function(color) {
		updateColor(1, color);
	}
});
$("#messagebarColor").spectrum({
	color: "#FFFFFF", allowEmpty: false, change: function(color) {
		updateColor(2, color);
	}, hide: function(color) {
		updateColor(2, color);
	}
});
$("#messageColor").spectrum({
	color: "#000000", allowEmpty: false, change: function(color) {
		updateColor(3, color);
	}, hide: function(color) {
		updateColor(3, color);
	}
});
$("#articleFull").change(function() {
	const checked = $(this).prop("checked");
	if (checked) document.getElementById("tokenBox").style.display = "block";
	else document.getElementById("tokenBox").style.display = "none";
});
//Storing some variables that are needed cross various methods
let titlebarColor = "#00B3E3", titleColor = "#FFFFFF", messageColor = "#FFFFFF", messageTextColor = "#000000", scanFeeds = [];
//Retrieve a list of portals
axios.post(handlerLink, {action: "getPortals"}).then(function(response) {
	populatePortalSelector(response.data.data);
	const portal = getUrlParameter('portalId');
	new M.Select($("#portalSelector").val(portal || -1).change(function() {
		const portalId = $(this).val();
		window.location.href = getRootUrl() + "?portalId=" + portalId;
	}).get(0));
	setCode();
});

//Retrieve a list of feeds
axios.post(handlerLink, {action: "getFeeds", portal: getUrlParameter("portalId") || -1}).then(function(response) {
	populateFeedSelector(response.data.data);
	new M.Select($("#feedSelector").change(function() {
		const feedId = $(this).val();
		if (scanFeeds.indexOf(parseInt(feedId)) !== -1) document.getElementById("memberBox").style.display = "block";
		else {
			document.getElementById("memberBox").style.display = "none";
			document.getElementById("tokenBox").style.display = "none";
		}
	}).get(0));
	setCode();
});

/**
 * Populate the portal selector with the retrieved portals
 */
function populatePortalSelector(portals) {
	let options = "<option value='-1'>Geen portal</option>";
	for (let i = 0; i < portals.length; ++i) {
		const portal = portals[i];
		options += "<option value='" + portal.id + "'>" + portal.name + "</option>"
	}
	document.getElementById("portalSelector").innerHTML = options;
}

/**
 * Populate the feed selector with the retrieved feeds for the selected portal
 */
function populateFeedSelector(feeds) {
	let options = "<option value='-1'>Selecteer feed</option>";
	for (let i = 0; i < feeds.length; ++i) {
		const feed = feeds[i];
		options += `<option value="${feed.id}" data-scan="${feed.scan}">${feed.name}</option>`;
		if (feed.scan === 0) scanFeeds.push(feed.id);
	}
	document.getElementById("feedSelector").innerHTML = options;
}

/**
 * Get a PHP-style GET-variable from the current URL
 */
function getUrlParameter(parameter) {
	const pageUrl = window.location.search.substring(1);
	const vars = pageUrl.split('&');
	for (let i = 0; i < vars.length; ++i) {
		const paramName = vars[i].split('=');
		if (paramName[0] === parameter) return paramName[1];
	}
}

/**
 * Get the current URL without any PHP-like GET-variables
 */
function getRootUrl() {
	return window.location.href.replace(/[*?].*/g, "");
}

/**
 * Encode the current preferences into a stringified JSON-object
 */
function encodePrefs() {
	const prefs = {
		feeds: ($("#feedSelector").val() ? $("#feedSelector").val() : "-1"),
		tbc: titlebarColor,
		ttc: titleColor,
		msgbc: messageColor,
		msgtc: messageTextColor,
		items: document.getElementById("itemCount").value,
		width: document.getElementById("widthCount").value,
		border: document.getElementById("cornerRadiusCount").value,
		ff: document.getElementById("fontFamily").value,
		shadow: document.getElementById("shadowBool").checked,
		full: document.getElementById("articleFull").checked,
		fs: document.getElementById("fontSize").value
	};
	const jsonString = JSON.stringify(prefs);
	return btoa(jsonString);
}

/**
 * Reload BizzFeed with the current preferences
 */
function reload() {
	const rawPrefs = encodePrefs();
	const current = document.getElementById("bizz");
	const src = current.getAttribute("src");
	const token = current.getAttribute("data-token");
	const newScript = document.createElement("script");
	newScript.setAttribute("data-token", token);
	newScript.setAttribute("data-prefs", rawPrefs);
	newScript.setAttribute("src", src);
	const currentFeed = document.getElementById("BizzFeed");
	if (currentFeed) currentFeed.parentNode.removeChild(currentFeed);
	current.parentNode.removeChild(current);
	newScript.id = "bizz";
	document.getElementById("previewPanel").appendChild(newScript);
	setCode();
}

/**
 * Activate the token in the token field
 */
function setCode() {
	const token = document.getElementById("tokenField").value;
	const code = "<script data-token=\"" + token + "\" data-prefs=\"" + encodePrefs() + "\" src=\"" + feedLink + "\"></script>";
	document.getElementById("htmlCode").innerHTML = code.replace(/[\u00A0-\u9999<>&]/gim, function(i) {
		return '&#' + i.charCodeAt(0) + ';';
	});
}

/**
 * Helper method to set the correct color based on an index
 */
function updateColor(type, color) {
	color = color.toHexString();
	switch (type) {
		case 0:
			titlebarColor = color || "null";
			break;
		case 1:
			titleColor = color || "null";
			break;
		case 2:
			messageColor = color || "null";
			break;
		case 3:
			messageTextColor = color || "null";
			break;
	}
}

/**
 * Activate the current token
 */
function activateToken() {
	const script = document.getElementById("bizz");
	script.setAttribute("data-token", document.getElementById("tokenField").value);
	reload();
}

function copyToClipboard(elem) {
	let target;
	// create hidden text element, if it doesn't already exist
	const targetId = "_hiddenCopyText_";
	const isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
	let origSelectionStart, origSelectionEnd;
	if (isInput) {
		// can just use the original source element for the selection and copy
		target = elem;
		origSelectionStart = elem.selectionStart;
		origSelectionEnd = elem.selectionEnd;
	} else {
		// must use a temporary form element for the selection and copy
		target = document.getElementById(targetId);
		if (!target) {
			target = document.createElement("textarea");
			target.style.position = "absolute";
			target.style.left = "-9999px";
			target.style.top = "0";
			target.id = targetId;
			document.body.appendChild(target);
		}
		target.textContent = elem.textContent;
	}
	// select the content
	const currentFocus = document.activeElement;
	target.focus();
	target.setSelectionRange(0, target.value.length);
	// copy the selection
	let succeed;
	try {
		succeed = document.execCommand("copy");
	} catch (e) {
		succeed = false;
	}
	// restore original focus
	if (currentFocus && typeof currentFocus.focus === "function") currentFocus.focus();
	if (isInput) {
		// restore prior selection
		elem.setSelectionRange(origSelectionStart, origSelectionEnd);
	} else {
		// clear temporary content
		target.textContent = "";
	}
	return succeed;
}
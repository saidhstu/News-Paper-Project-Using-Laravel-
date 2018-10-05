//Setting some constants
// var BASEURI = "http://localhost:8000";
var BASEURI = "https://bizznieuws.nl";
var feedAddress = BASEURI + "/bizzfeed/items/";
var styleAddress = BASEURI + "/css/bizzfeed.css";
//Storing some variables for later
var script, token, json, myAddress, root;
//Storing preferences
var feeds, titleback, titletext, messageback, messagetext, items, myWidth, border, fontFamily, shadow, full, fontSize;
if (document.currentScript) script = document.currentScript;
if (script) {
	token = script.getAttribute("data-token");
	BizzNieuws_reloadPrefs(script.getAttribute("data-prefs"));
	if (feeds.length > 0) {
		root = document.createElement("div");
		root.id = "BizzFeed";
		BizzNieuws_linkStyleSheet();
		//Add root container to the webpage
		if (document.body.contains && !document.body.contains(script))
			document.body.appendChild(root);
		else
			script.parentNode.insertBefore(root, script.nextSibling);
		root.style.maxWidth = myWidth === "0" ? "100%" : myWidth + "px";
		root.style.fontFamily = fontFamily;
		root.style.fontSize = fontSize + "pt";
		BizzNieuws_loadFeed();
	}
}

/**
 * Sets the preferences to prefs
 */
function BizzNieuws_reloadPrefs(prefs) {
	var json = null;
	try {
		var rawString = atob(prefs);
		json = JSON.parse(rawString);
	} catch (e) {
		console.error("Could not parse BizzFeed data:", prefs);
	}
	var myFeeds = BizzNieuws_getOrDefault(json, "feeds", undefined);
	if (myFeeds !== undefined) {
		if (myFeeds.indexOf(":") !== -1)
			feeds = myFeeds.split(":");
		else
			feeds = [myFeeds];
		titleback = BizzNieuws_getOrDefault(json, "tbc", "rgb(0, 179, 227)");
		titletext = BizzNieuws_getOrDefault(json, "ttc", "white");
		messageback = BizzNieuws_getOrDefault(json, "msgbc", "white");
		messagetext = BizzNieuws_getOrDefault(json, "msgtc", "black");
		items = BizzNieuws_getOrDefault(json, "items", 10);
		myWidth = BizzNieuws_getOrDefault(json, "width", 200);
		border = BizzNieuws_getOrDefault(json, "border", 5);
		fontFamily = BizzNieuws_getOrDefault(json, "ff", "serif");
		shadow = BizzNieuws_getOrDefault(json, "shadow", true);
		full = BizzNieuws_getOrDefault(json, "full", false);
		fontSize = BizzNieuws_getOrDefault(json, "fs", 10);
	}
}

/**
 * Get a value from an json object/array or return defaultValue when that value couldn't be found or is undefined/null
 */
function BizzNieuws_getOrDefault(json, name, defaultValue) {
	return json === undefined || json === null || json[name] === undefined || json[name] === null ? defaultValue : json[name];
}

/**
 * Returns true if the current URL ends with #art=<number>
 */
function BizzNieuws_hasPermaLink() {
	return window.location.href.match(/.(#art=\d)/g);
}

/**
 * Fetches the feed data (in JSON format) from Bizznieuws.
 * When fetching is complete; open article if a PermaLink was detected
 */
function BizzNieuws_loadFeed() {
	var request = undefined;
	if (window.XMLHttpRequest)
		request = new XMLHttpRequest();
	else
		request = new ActiveXObject("Microsoft.XMLHTTP");
	request.open("POST", feedAddress, true);
	request.onreadystatechange = function() {
		if (request.readyState === 4)
			if (request.status === 200) {
				json = JSON.parse(request.responseText);
				BizzNieuws_populateRootContainer();
				//Open article if a PermaLink was detected
				if (!json.error && BizzNieuws_hasPermaLink()) {
					var id = window.location.href.match(/#art=(\d.*)/g)[0].substr(5);
					BizzNieuws_openArticle(id);
				}
			} else console.error("Could not get BizzFeed data: " + request.responseText);
	};
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.send(`token=${token}&feeds=${feeds}&full=${full}`);
}

/**
 * Add a link for the required CSS
 */
function BizzNieuws_linkStyleSheet() {
	var linkElement = document.createElement("link");
	linkElement.setAttribute("rel", "stylesheet");
	linkElement.setAttribute("type", "text/css");
	linkElement.setAttribute("href", styleAddress);
	root.appendChild(linkElement);
}

/**
 * Add cards for all fetched articles to the root container
 */
function BizzNieuws_populateRootContainer() {
	if (json.error)
		root.innerHTML = "<strong>BizzFeed error!</strong>";
	else {
		console.log(json);
		for (var i = 0; i < json.data.length && i < items; ++i) {
			var item = json.data[i];
			//Whole card is a link!!
			var link = document.createElement("a");
			link.className = "BizznieuwsCardLink";
			link.setAttribute("href", "javascript:void(0)");
			if (item.hasContent)
				if (full)
					link.setAttribute("onclick", "BizzNieuws_openArticle(" + item.id + ")");
				else
					link.setAttribute("onclick", "window.open('http://bizznieuws.nl" + item.link + "', '_blank');");
			else
				link.setAttribute("onclick", "window.open('" + item.link + "', '_blank');");
			root.appendChild(link);
			//Card container
			var container = document.createElement("div");
			container.style.backgroundColor = messageback === "null" ? "transparent" : messageback;
			container.style.color = messagetext === "null" ? "inherit" : messagetext;
			container.className = "BizznieuwsCard" + (shadow ? " BizznieuwsShadowBox" : "");
			container.style.borderRadius = border + "px";
			container.style.webkitBorderLeftRadius = border + "px";
			container.style.mozBorderLeftRadius = border + "px";
			link.appendChild(container);
			//Card title
			var title = document.createElement("div");
			title.className = "BizznieuwsCardTitle";
			title.style.backgroundColor = titleback === "null" ? "transparent" : titleback;
			title.style.color = titletext === "null" ? "inherit" : titletext;
			title.style.borderTopLeftRadius = border + "px";
			title.style.borderTopRightRadius = border + "px";
			title.style.webkitBorderTopLeftRadius = border + "px";
			title.style.webkitBorderTopRightRadius = border + "px";
			title.style.mozBorderTopLeftRadius = border + "px";
			title.style.mozBorderTopRightRadius = border + "px";
			title.style.fontSize = (parseInt(fontSize) + 1) + "pt";
			title.innerHTML = "<strong>" + item.title + "</strong>";
			container.appendChild(title);
			//Card content (article description)
			var content = document.createElement("div");
			content.className = "BizznieuwsCardContent";
			container.appendChild(content);
			if (item.image != null) {
				//Image
				var img = document.createElement("img");
				img.setAttribute("src", item.image);
				img.style.width = "100%";
				img.style.maxWidth = "200px";
				img.style.maxheight = "200px";
				img.style.display = "block";
				img.style.margin = "0 auto";
				content.appendChild(img);
			}
			//Card content real
			var realContent = document.createElement("div");
			realContent.style.paddingTop = "15px";
			realContent.innerHTML = item.description;
			content.append(realContent);
			//Date
			var date = document.createElement("span");
			date.style.fontSize = "8pt";
			date.innerHTML = "Datum: " + new Date(item.pubdate.date).toDateString();
			content.appendChild(date);
			//Source
			content.appendChild(document.createElement('br'));
			var date = document.createElement("span");
			date.style.fontSize = "8pt";
			date.innerHTML = "Bron: " + item.feedName;
			content.appendChild(date);
		}
	}
}

/**
 * Opens or closes an article. Use id -1 to close an article and repopulate the root container
 * @param id the article's id (-1 to close)
 */
function BizzNieuws_openArticle(id) {
	//Clear root container and add stylesheet
	root.innerHTML = "";
	BizzNieuws_linkStyleSheet();
	if (id === -1) {
		//Replace PermaLink URL with "normal" URL
		window.history.pushState("", "", myAddress);
		BizzNieuws_populateRootContainer();
	} else {
		//Find article data with the given id
		var article = undefined;
		for (var i = 0; i < json.data.length; ++i) {
			var cur = json.data[i];
			if (cur.id == id) {
				article = cur;
				break;
			}
		}
		//Replace current URL with PermaLink URL
		myAddress = window.location.href.replace(/(#art=\d.*)/g, "");
		window.history.pushState("", "", myAddress + "#art=" + id);
		if (article !== undefined)
			BizzNieuws_populateArticle(article);
	}
}

/**
 * Populate and styles the root container with the given article data
 * @param article the article data in JSON format
 */
function BizzNieuws_populateArticle(article) {
	//Article container
	var container = document.createElement("div");
	container.className = "BizznieuwsCard" + (shadow ? " BizznieuwsShadowBox" : "");
	container.style.backgroundColor = messageback === "null" ? "transparent" : messageback;
	root.appendChild(container);
	//Close link
	var close = document.createElement("div");
	close.style.backgroundColor = titleback === "null" ? "transparent" : titleback;
	close.className = "BizznieuwsClose";
	close.innerHTML = "<a href='javascript:void(0)' onclick='BizzNieuws_openArticle(-1)' style='color: " + (titletext === "null" ? "inherit" : titletext) + "'><strong>Sluiten</strong></a>";
	close.style.borderTopLeftRadius = border + "px";
	close.style.borderTopRightRadius = border + "px";
	close.style.webkitBorderTopLeftRadius = border + "px";
	close.style.webkitBorderTopRightRadius = border + "px";
	close.style.mozBorderTopLeftRadius = border + "px";
	close.style.mozBorderTopRightRadius = border + "px";
	container.appendChild(close);
	//Article content
	var content = document.createElement("div");
	content.className = "BizznieuwsArticleContent";
	content.innerHTML = "<strong>" + article.description + "</strong><hr><p>" + article.content + "</p>";
	content.style.color = messagetext === "null" ? "inherit" : messagetext;
	container.appendChild(content);
}

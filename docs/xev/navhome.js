var myNavBar = new NavBar(0);
var tempMenu;
// Colors go in this order on .setcolors method below
//1 this.borderColor=""; (whole background & divider color)
//2 this.hdrFgColor="";  (or header words text color)
//3 this.hdrBgColor=""; (header overlay-bg color)
//4 this.hdrHiFgColor=""; (header words mouseover color)
//5 this.hdrHiBgColor=""; (header overlay-bg mouseover color)
//6 this.itmFgColor=""; (menu items text color)
//7 this.itmBgColor=""; (menu items bg color)
//8 this.itmHiFgColor=""; (menu items text mouseover color)
//9 this.itmHiBgColor=""; (menu items bg mouseover color)

myNavBar.setColors("black", "black", "990000", "990000", "black", "black", "990000", "990000", "black");

myNavBar.setAlign("center");

//(first# menuitem width, second# submenuitem width)
tempMenu = new NavBarMenu(81,120);
tempMenu.addItem(new NavBarMenuItem("Home", "http://www.shadowclan.org/xev/index.html"));
myNavBar.addMenu(tempMenu);

tempMenu = new NavBarMenu(81, 120);
tempMenu.addItem(new NavBarMenuItem("About Us", ""));
tempMenu.addItem(new NavBarMenuItem("Become a Member", "http://www.shadowclan.org/xev/registration.html"));

tempMenu.addItem(new NavBarMenuItem("Introduction", "http://www.shadowclan.org/xev/intro.html"));
tempMenu.addItem(new NavBarMenuItem("Information", "http://www.shadowclan.org/xev/info.html"));
tempMenu.addItem(new NavBarMenuItem("Roleplay",  "http://www.shadowclan.org/xev/roleplay.html"));
tempMenu.addItem(new NavBarMenuItem("Character Creation", "http://www.shadowclan.org/xev/creation.html"));
tempMenu.addItem(new NavBarMenuItem("Racial Guides", "http://www.shadowclan.org/xev/raceguides.html"));
tempMenu.addItem(new NavBarMenuItem("Languages", "http://www.shadowclan.org/xev/languages.html"));
tempMenu.addItem(new NavBarMenuItem("Shadowclan Main", "http://www.shadowclan.com"));



myNavBar.addMenu(tempMenu);

tempMenu = new NavBarMenu(81, 120);
tempMenu.addItem(new NavBarMenuItem("Clan Info", ""));
tempMenu.addItem(new NavBarMenuItem("Clan Calendar", "http://www.shadowclan.org/xev/calendar.html"));
tempMenu.addItem(new NavBarMenuItem("Clan Registration", "http://www.shadowclan.org/xev/registration.html"));
tempMenu.addItem(new NavBarMenuItem("Clan Rosters",  "http://www.shadowclan.org/xev/rosters.html"));
tempMenu.addItem(new NavBarMenuItem("Character Stories",  "http://www.shadowclan.org/xev/stories.html"));


myNavBar.addMenu(tempMenu);

tempMenu = new NavBarMenu(81, 120);
tempMenu.addItem(new NavBarMenuItem("Help", ""));
tempMenu.addItem(new NavBarMenuItem("FAQ", "http://www.shadowclan.org/xev/faq.html"));
tempMenu.addItem(new NavBarMenuItem("Links", "http://www.shadowclan.org/xev/links.html"));

myNavBar.addMenu(tempMenu);

tempMenu = new NavBarMenu(81, 120);
tempMenu.addItem(new NavBarMenuItem("Blah", ""));
tempMenu.addItem(new NavBarMenuItem("Message Board", "http://www.shadowclan.com/darkmoot/"));
tempMenu.addItem(new NavBarMenuItem("Submit News", "mailto:saphelps@fedex.com eatagoat@adelphia.net xevxaya@hotmail.com?subject=submitted shadowclan news"));
tempMenu.addItem(new NavBarMenuItem("Submit Event", "mailto:saphelps@fedex.com eatagoat@adelphia.net xevxaya@hotmail.com?subject=submitted shadowclan event"));
tempMenu.addItem(new NavBarMenuItem("Contact Clan", "mailto:saphelps@fedex.com eatagoat@adelphia.net xevxaya@hotmail.com?subject=From Clan Site"));

myNavBar.addMenu(tempMenu);

tempMenu = new NavBarMenu(81, 120);
tempMenu.addItem(new NavBarMenuItem("Join Us", "http://www.shadowclan.org/xev/registration.html"));

myNavBar.addMenu(tempMenu);


function init()
{
  var sbWidth = 16;    // Guesstimate of scrollbar width, necessary for NS4.
    if (isMinNS4)
       sbWidth = 16;
  var img;
  myNavBar.resize(getWindowWidth()-200 - getWindowWidth()/14);
  myNavBar.create();
	

  // Find the position of the embedded image and move bar accordingly, note
  // that we have to adjust for the table's cell padding.

  img = getImage("placeholder");
  myNavBar.moveTo(getImagePageLeft(img) - 0, getImagePageTop(img) - 0);
}
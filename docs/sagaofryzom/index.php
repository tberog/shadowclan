<html>
<head>
<title>Shadowclan - In Saga Of Ryzom</title><style type="text/css">.f11 { font-family:Arial; font-size: 11px}
.link1:link   { font-family:Arial; font-size:14px; color: #000000; text-decoration: underline }
.link1:visited{ font-family:Arial; font-size:14px; color: #000000; text-decoration: underline }
.link1:active { font-family:Arial; font-size:14px; color: #000000; text-decoration: underline }
.link1:hover  { font-family:Arial; font-size:14px; color: #660000; text-decoration: underline }
#scrollbox {width:650px; height:450px; overflow:auto; border:0px; solid: #aaa;}
</style>
</head>
<!-- Made by Snagaglob for the use of the clan. Design made by Oribal. -->
<body width="1024" leftmargin="0" topmargin="0">
<!-- Main Table -->
	<?php $page="about";?>
	<table cellpadding="0" cellspacing="0" width="1024">	<!-- Shadowclan Header Picture-->
		<tr height="159">
			<td colspan="3" background="Images/header.jpg">
			</td>
		</tr>	<!-- Upper Picture Setup-->
		<tr height="55">
			<td width="229" background="Images/upperleft.jpg">
			</td>	<!-- Links -->
			<td width="668" background="Images/uppermiddle.jpg">
				<!-- Load Links -->
			</td>
			<td width="127" background="Images/upperright.jpg">
			</td>
		</tr>	<!-- Nav Bar -->
		<tr height="518">
			<td width="229">
				<table cellpadding="0" cellspacing="0" width="229">	<!-- Link -->
					<tr height="33">
						<td width="229"><a href="body.php?page=about">
						<?php if ( $page == "about" ) {	$link = "on";} else {$link = "off";}?>						
						<img src="Images/link1<?php echo $link; ?>.jpg" border="0"><br>
						</td>
					</tr>	<!-- Spacer/Pic -->
					<tr height="67">
						<td width="229" background="Images/mid1.jpg">
						</td>
					</tr>	<!-- Link -->
					<tr height="33">
						<td width="229"><a href="body.php?page=join">
						<?php if ( $page == "join" ) { $link = "on";} else {$link = "off";}?>
						<img src="Images/link2<?php echo $link; ?>.jpg" border="0"></a><br>
						</td>
					</tr>	<!-- Spacer/Pic -->
					<tr height="67">
						<td width="229" background="Images/mid2.jpg">
						</td>
					</tr>	<!-- Link -->
					<tr height="33">
						<td width="229"><a href="body.php?page=expect">
						<?php if ( $page == "expect" or $page =="language" or $page =="ranks" or $page =="agenda") { $link = "on";} else {$link = "off";}?>
						<img src="Images/link3<?php echo $link; ?>.jpg" border="0"></a><br>
						</td>
					</tr>	<!-- Spacer/Pic -->
					<tr height="67">
						<td width="229" background="Images/mid3.jpg">
						</td>
					</tr>	<!-- Link -->
					<tr height="33">
						<td width="229"><a href="http://www.shadowclan.org/darkmoot/viewforum.php?f=98" target="_blank"><img src="Images/link4off.jpg" border="0"></a><br>
						</td>
					</tr>
					<tr height="67">
						<td width="229" background="Images/mid4.jpg">
						</td>
					</tr>
					<tr height="118">
						<td width="229" background="Images/bottomleft.jpg">
						</td>
				</table>
			</td>	<!-- Main Page Information-->
			<td width="668" valign="top" align="center" background="Images/mainback.jpg">
				<!-- Main Scroll -->
				<div align="left" id="scrollbox">
					<?php
						$handle = fopen(($page.".txt"), "r");
							while (!feof($handle) && (brek!=1)) {
								$buffer = fgets($handle, 4096);
								echo $buffer;
							}
						fclose($handle);
						?>
				</div>
			</td>
			<td width="127" background="Images/rightside.jpg">
		</tr>
		<tr height="37">
			<td colspan="2" background="Images/bottomleft1.jpg">
			</td>
			<td width="127" background="Images/bottomright.jpg">
			</td>
		</tr>
	</table>
</body>
</html>

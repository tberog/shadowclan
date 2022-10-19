#!/usr/local/bin/perl
$mail_prog = '/usr/sbin/sendmail' ;


&GetFormInput;


@valid_ref = ('http://www.shadowclan.org') ;
foreach $ref (@valid_ref) {
if ($ENV{'HTTP_REFERER'} =~ m/$ref/i) {$is_valid = 1 ; last ;}
}
if (! $is_valid) {
print "Content-type: text/html\n\nERROR - Invalid Referrer\n" ;
exit 0 ;
}

$name = $field{'name'} ;	 
$email = $field{'email'} ;	 
$subject = $field{'subject'} ;	 
$comment = $field{'comment'} ;	 
$Submit = $field{'Submit'} ;	 
$Clearform = $field{'Clearform'} ;	 

$message = "" ;
$found_err = "" ;

$errmsg = "<p>Field 'name' must be filled in.</p>\n" ;

if ($name eq "") {
	$message = $message.$errmsg ;
	$found_err = 1 ; }


$errmsg = "<p>Please enter a valid email address</p>\n" ;

if (($email =~ /(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/) || ($email !~ /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z0-9]+)(\]?)$/)) {
	$message = $message.$errmsg ;
	$found_err = 1 ; }


$errmsg = "<p>Field 'subject' must be filled in.</p>\n" ;

if ($subject eq "") {
	$message = $message.$errmsg ;
	$found_err = 1 ; }


$errmsg = "<p>Field 'comment' must be filled in.</p>\n" ;

if ($comment eq "") {
	$message = $message.$errmsg ;
	$found_err = 1 ; }

if ($found_err) {
	&PrintError; }


$recip = "jihan\@irekei.org" ;
$frm_ = "SC Irekei web site" ;
$sbj_ = "Contact form submission" ;
$hd_ = $recip.$frm_.$sbj ;
if (($hd =~ /(\n|\r)/m) || ($recip =~ /(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/) || ($recip !~ /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z0-9]+)(\]?)$/)) {
print "Fatal Error - Invalid email" ; 
exit 0; 
}

open (MAIL, "|$mail_prog -t");
print MAIL "To: $recip\n";
print MAIL "Reply-to: $frm_\n";
print MAIL "From: $frm_\n";
print MAIL "Subject: $sbj_\n";
print MAIL "\n\n";
print MAIL "".$name."\n" ;
print MAIL "".$email."\n" ;
print MAIL "".$subject."\n" ;
print MAIL "".$comment."\n" ;
print MAIL "\n\n";
close (MAIL);
print "Location: contact.html\nURI: contact.html\n\n" ;

sub PrintError { 
print "Content-type: text/html\n\n";
print $message ;
print "<p> Please use your browser's Back button to return to the form. </p>" ;

exit 0 ;
return 1 ; 
}
sub GetFormInput {

	(*fval) = @_ if @_ ;

	local ($buf);
	if ($ENV{'REQUEST_METHOD'} eq 'POST') {
		read(STDIN,$buf,$ENV{'CONTENT_LENGTH'});
	}
	else {
		$buf=$ENV{'QUERY_STRING'};
	}
	if ($buf eq "") {
			return 0 ;
		}
	else {
 		@fval=split(/&/,$buf);
		foreach $i (0 .. $#fval){
			($name,$val)=split (/=/,$fval[$i],2);
			$val=~tr/+/ /;
			$val=~ s/%(..)/pack("c",hex($1))/ge;
			$name=~tr/+/ /;
			$name=~ s/%(..)/pack("c",hex($1))/ge;

			if (!defined($field{$name})) {
				$field{$name}=$val;
			}
			else {
				$field{$name} .= ",$val";
				
				#if you want multi-selects to goto into an array change to:
				#$field{$name} .= "\0$val";
			}


		   }
		}
return 1;
}


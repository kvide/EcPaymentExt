<?php
#A


#B


#C

#D
$lang['desc_event_on_incoming_event'] = 'Asyncrhonous Payment Gateway event';


#E


#F
$lang['friendlyname'] = 'Base module for payment gateways';

#G


#H
$lang['help'] = <<<EOT
<h3>What does this do?</h3>
<p>This module provides a skeleton API for other payment gateway modules in CMSMS Ecommerce extensions. It provides no interface or functionality of its own</p>
<h3>How do I use it?</h3>
<p>This module should be installed prior to installing any other of the payment gateway modules that are compatible with CMSMS Ecommerce extensions.</p>
<h3>Support</h3>
<p>This module does not include commercial support. However, there are a number of resources available to help you with it:</p>
<ul>
<li>For the latest version of this module, FAQs, or to file a Bug Report or buy commercial support, please visit the cms development forge at <a href="http://dev.cmsmadesimple.org">dev.cmsmadesimple.org</a>.</li>
<li>Additional discussion of this module may also be found in the <a href="http://forum.cmsmadesimple.org">CMS Made Simple Forums</a>.</li>
<li>The author, calguy1000 all can often be found in the <a href="irc://irc.freenode.net/#cms">CMS IRC Channel</a>.</li>
<li>Lastly, you may have some success emailing the author(s) directly.</li>  
</ul>

<h3>Copyright and License</h3>
<p>Copyright &copy; 2008, Robert Campbel <a href="mailto:calguy1000@cmsmadesimple.org">&lt;calguy1000@cmsmadesimple.org&gt;</a>. All Rights Are Reserved.</p>
<p>This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.</p>
<p>However, as a special exception to the GPL, this software is distributed
as an addon module to CMS Made Simple.  You may not use this software
in any Non GPL version of CMS Made simple, or in any version of CMS
Made simple that does not indicate clearly and obviously in its admin 
section that the site was built with CMS Made simple.</p>
<p>This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
Or read it <a href="http://www.gnu.org/licenses/licenses.html#GPL">online</a></p>
EOT;
$lang['help_event_on_incoming_event'] = <<<EOT
<p>Sent when a transaction or event is received asynchronously by a payment gateway.</p>
<h4>Parameters:</h4>
<ul>
  <li>"order_id" - Orders module Order id</li>
  <li>"invoice" - Orders invoice string <em>(note that only one of order_id or invoice will be available)</em></li>
  <li>"transaction" - Transaction id</li>
  <li>"payment_status" - Payment Status</li>
  <li>"amount" - Payment amount</li>
  <li>"gateway" - The name of the originating gateway module</li>
<li>"message" - (optional) message.</li>
</ul>
EOT;

#I


#J


#K


#L


#M
$lang['moddescription'] = 'A base (skeleton) module for development of payment gateways to use with CMSMS Ecommerce extensions';

#N


#O


#P
$lang['postinstall'] = 'This module is now installed... You can proceed with installing other payment gateways';
$lang['postuninstall'] = 'This module is now uninstalled';

#Q


#R
$lang['really_uninstall'] = 'Are you sure you want to do this?  Proceeding may cause errors in a working E-commerce site?';

#S


#T


#U


#V


#W


#X


#Y


#Z


?>
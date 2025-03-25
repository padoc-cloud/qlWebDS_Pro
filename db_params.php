<?php

// qlWeb Internet Scripts.
// Copyright (c) 2007-2025 Contact USA Inc. (http://www.qlWebScripts.com) All Rights Reserved.

// On some hosting accounts such as GoDaddy.com
// "localhost" (line 11) might need to be replaced with a database URL/connection string, "mysql" or IP Address
// Avoid the word "directory" in your Database Name or Admin Login
// Some servers seem to have a problem with this. You may use "dir" as part of the name

define('DB_HOST', 'localhost');
define('DB_NAME', 'ql-test');    // needs to be changed by your database name
define('DB_USER', 'root');        
define('DB_PASS', '12345678');

// In most cases, you will not want to change line 19: define('DB_TABLE_PREFIX', 'qlWebDS_');
// If your old versions's prefix is: qlweb_ instead of: qlWebDS_ - then DO CHANGE the following prefix to: qlwebDS_

define('DB_TABLE_PREFIX', 'qlWebDS_');

// CAUTION!!! Do NOT change below this line!!!

// <!-- Start License
/*
**********************************************************************************************************************************************************
All below listed notices MUST remain intact.
Copyright (c) 2007-2025 Contact USA Inc. (http://www.qlWebScripts.com) All Rights Reserved.
Software License Agreement - qlWeb Internet Scripts:
Commercial - Branded License unless an Unbranded License is Purchased.
Commercial - Single One Domain License unless a Multi Domain or an Unlimited Domain License is Purchased.

All files, copyright notices, LICENSE.TXT file, "Copyright (c) Contact USA Inc. All Rights Reserved." 
and "Powered by: qlWebDS" or "Powered by: qlWeb Directory Script" or "Powered by: qlWebDM" or "Powered by: qlWeb Directory Management" 
or "Powered by: qlWebDS Deluxe" or "Powered by: qlWebDS Premium" or "Powered by: qlWebDS Pro" or "Powered by: qlWebDS Supreme" 
or "Powered by: qlWebQS" or "Powered by: qlWebCS" or "Powered by: qlWebAS" or "Powered by: qlWebSD" or "Powered by: qlWebEC" 
or "Powered by: qlWebCM" or "Powered by: qlWebMS" or "Powered by: qlWebDL" or "Powered by: qlWebAM" or "Powered by: qlWebBD" or "Powered by: qlWebDA" 
backlinks (link backs) MUST remain intact and active "follow" URL links on the home page of the installed Software 
pointing to http://www.qlWebScripts.com unless an Unbranded License is Purchased.

Unbranded, Multi Domain and Unlimited Domain Licenses may be purchased at http://www.qlWebScripts.com/modules.php?name=Licenses
**********************************************************************************************************************************************************
License
1. Under this Software License Agreement (the "Agreement"), Contact USA Inc. (the "Vendor") grants to the user (the "Licensee") a nonexclusive and nontransferable license (the "License") to use qlWeb Internet Scripts ("qlWeb") (the "Software").
2. "Software" includes the php script, templates, the source code and any related printed, electronic and online documentation and any other files that may accompany the product.
3. Title, copyright, intellectual property rights and distribution rights of the Software remain exclusively with the Vendor. Intellectual property rights include the look and feel of the Software. This Agreement constitutes a license for use only and is not in any way a transfer of ownership rights to the Software.
4. The Software may be loaded onto no more than one computer or no more than one Website and it may only be used on a single domain unless additional licenses or unlimited domain licenses are purchased. A single copy may be made for backup purposes only.
5. The rights and obligations of this Agreement are personal rights granted to the Licensee only. The Licensee may not transfer or assign any of the rights or obligations granted under this Agreement to any other person or legal entity. The Licensee may not make available the Software for use by one or more third parties.
6. The Licensee may not redistribute, sell or otherwise share this software in whole or in part without the consent of the Vendor. Licensee also agrees to retain active "follow" (backlinks) link backs ("Powered by: qlWebDS" or "Powered by: qlWeb Directory Script" or "Powered by: qlWebDM" or "Powered by: qlWeb Directory Management" 
   or "Powered by: qlWebDS Deluxe" or "Powered by: qlWebDS Premium" or "Powered by: qlWebDS Pro" or "Powered by: qlWebDS Supreme" or "Powered by: qlWebQS" or "Powered by: qlWebCS" or "Powered by: qlWebAS" or "Powered by: qlWebSD" or "Powered by: qlWebEC" or "Powered by: qlWebCM" or "Powered by: qlWebMS" or "Powered by: qlWebDL" or "Powered by: qlWebAM" or "Powered by: qlWebBD" 
   or "Powered by: qlWebDA") URL links pointing to http://www.qlWebScripts.com on the home page of the installed Software (unless a separate unbranded license - powered by removal license is purchased) as well as all files and copyright notices, LICENSE.TXT file, "Copyright (c) Contact USA Inc. All Rights Reserved.".
7. Failure to comply with any of the terms under the License section will be considered a material breach of this Agreement.
License Fee
8. The original purchase price paid by the Licensee will constitute the entire license fee and is the full consideration for this Agreement. Licensee will be entitled to future upgrades at $5 for Deluxe Version, $10 for Premium Version, $15 for Pro Version and $20 for Supreme Version.
Limitation of Liability
9. The Software is provided by the Vendor and accepted by the Licensee "as is". Liability of the Vendor will be limited to a maximum of the original purchase price of the Software. The Vendor will not be liable for any general, special, incidental or consequential damages including, but not limited to, loss of production, loss of profits, loss of revenue, loss of data, or any other business or economic disadvantage suffered by the Licensee arising out of the use or failure to use the Software.
10. The Vendor makes no warranty expressed or implied regarding the fitness of the Software for a particular purpose or that the Software will be suitable or appropriate for the specific requirements of the Licensee.
11. The Vendor does not warrant that use of the Software will be uninterrupted or error-free. The Licensee accepts that software in general is prone to bugs and flaws within an acceptable level as determined in the industry.
Warrants and Representations
12. The Vendor warrants and represents that it is the copyright holder of the Software. The Vendor warrants and represents that granting the license to use this Software is not in violation of any other agreement, copyright or applicable statute.
Acceptance
13. All terms, conditions and obligations of this Agreement will be deemed to be accepted by the Licensee ("Acceptance") on installation of the Software.
Term
14. The term of this Agreement will begin on Acceptance and is perpetual.
Termination
15. This Agreement will be terminated and the License forfeited where the Licensee has failed to comply with any of the terms of this Agreement or is in breach of this Agreement. On termination of this Agreement for any reason, the Licensee will promptly destroy the Software or return the Software to the Vendor.
Force Majeure
16. The Vendor will be free of liability to the Licensee where the Vendor is prevented from executing its obligations under this Agreement in whole or in part due to Force Majeure, such as earthquake, typhoon, flood, fire, and war or any other unforeseen and uncontrollable event where the Vendor has taken any and all appropriate action to mitigate such an event.
Governing Law
17. The Parties to this Agreement submit to the jurisdiction of the courts of the State of New York for the enforcement of this Agreement or any arbitration award or decision arising from this Agreement. This Agreement will be enforced or construed according to the laws of the State of New York.
Miscellaneous
18. This Agreement can only be modified in writing signed by both the Vendor and the Licensee.
19. This Agreement does not create or imply any relationship in agency or partnership between the Vendor and the Licensee.
20. Headings are inserted for the convenience of the parties only and are not to be considered when interpreting this Agreement. Words in the singular mean and include the plural and vice versa. Words in the masculine gender include the feminine gender and vice versa. Words in the neuter gender include the masculine gender and the feminine gender and vice versa.
21. If any term, covenant, condition or provision of this Agreement is held by a court of competent jurisdiction to be invalid, void or unenforceable, it is the parties' intent that such provision be reduced in scope by the court only to the extent deemed necessary by that court to render the provision reasonable and enforceable and the remainder of the provisions of this Agreement will in no way be affected, impaired or invalidated as a result.
22. This Agreement contains the entire agreement between the parties. All understandings have been included in this Agreement. Representations which may have been made by any party to this Agreement may in some way be inconsistent with this final written Agreement. All such statements are declared to be of no value in this Agreement. Only the written terms of this Agreement will bind the parties.
23. This Agreement and the terms and conditions contained in this Agreement apply to and are binding upon the Vendor's successors and assigns.
Notices
24. All notices to the Vendor under this Agreement are to be provided at the following address:
**********************************************************************************************************************************************************
Script Info:
**********************************************************************************************************************************************************
Custom PHP/MySQL Internet Scripts, Hosting and Web Design
Contact USA Inc.
qlWebScripts.com Support Team
16350 Fairway Woods Dr.
Suite 1805
Fort Myers, FL 33908-5331  USA

Telephone - Local: 718-743-4040
Outside of the U.S. call: +1 718-743-4040
E-mail: Script@qlWebScripts.com

Info:															Commercial - Branded License - requires "Powered by: qlWeb" intact and active "follow" URL link on the home page of the installed Software 																pointing to http://www.qlWebScripts.com unless an Unbranded License is Purchased.
Commercial Scripts:								qlWeb Internet Scripts: qlWebDS, qlWebDM, qlWebQS, qlWebCS, qlWebAS, qlWebSD, qlWebEC, qlWebCM, qlWebMS, qlWebDL, 																				qlWebAM, qlWebBD and qlWebDA.
Copyright:												Copyright (c) 2007-2022 Contact USA Inc. (http://www.qlWebScripts.com) All Rights Reserved.
Licenses:													All Licenses for qlWebDS, qlWebDM, qlWebQS, qlWebCS, qlWebAS, qlWebSD, qlWebEC, qlWebCM, qlWebMS, qlWebDL, qlWebAM, 																			qlWebBD and qlWebDA are Single One Domain Licenses unless a Multi Domain or an Unlimited Domain License is Purchased.
Use:															All files, copyright notices, LICENSE.TXT file, "Copyright (c) Contact USA Inc. All Rights Reserved." AND all "Powered by" backlinks (link 																		backs) MUST remain intact and active "follow" URL links on the home page of the installed Software pointing to http://																										www.qlWebScripts.com unless an Unbranded License is Purchased.
Technical Requirements:							Linux Apache Server (preferred) with Mod Rewrite (preferred) or any other Server with PHP 4.0 or higher and MySQL 4.1 or higher.
Main Site:												http://www.qlWebScripts.com
Orders:													http://www.qlWebScripts.com/modules.php?name=Order
Upgrades:												http://www.qlWebScripts.com/modules.php?name=Upgrades
Unbranded Licenses:								http://www.qlWebScripts.com/modules.php?name=Licenses
Multi and Unlimited Domains Licenses:	http://www.qlWebScripts.com/modules.php?name=Licenses
Customization and Installation Help:		http://www.qlWebScripts.com/modules.php?name=Installation_Customization
Demo Site:												
Free Version Download:							
Features - All Versions:							http://www.qlWebScripts.com/modules.php?name=qlWebDS_Features
qlWeb Scripts Affiliate Program:				http://www.qlWebScripts.com/affiliates/
qlWeb Scripts Manuals:							http://www.qlWebScripts.com/modules.php?name=Manuals
License Info:											http://www.qlWebScripts.com/modules.php?name=FAQ
Software Piracy:										http://www.qlWebScripts.com/modules.php?name=Piracy

Other PHP/MySQL qlWeb Internet Scripts by Contact USA Inc.
qlWebDS - Directory Script (Free, Deluxe, Premium, Pro and Supreme Versions)
qlWebDM - Directory Management Script (Multi Directory Installation and Domain Management)
qlWebQS - Quick Site Script (Build a Website in minutes)
qlWebCS - Classified Script
qlWebAS - Article Script
qlWebSD - Scripts Directory
qlWebEC - eCommerce Script
qlWebCM - Content Management Script
qlWebMS - Membership Script
qlWebDL - Directory List Script
qlWebAM - Affiliate Manager Script
qlWebBD - Bid Directory Script
qlWebDA - Directory Adder Script

Copyright (c) 2007-2025 Contact USA Inc. (http://www.qlWebScripts.com) All Rights Reserved.
All above listed notices MUST remain intact.
**********************************************************************************************************************************************************
*/
// End License //-->

?>

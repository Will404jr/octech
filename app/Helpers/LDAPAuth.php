<?php

namespace App\Helpers;

use DomainException;
use Exception;
use Illuminate\Support\Facades\Log;

class LDAPAuth
{
    protected $ldap_dn, $ldap_host;

    public function __construct()
    {
        // $ldap_host =  config('ldapconnection.LDAP_HOST'); // 'ldap://LDAP_SERVER'; // Your LDAP server address
        // $ldap_dn = config('ldapconnection.LDAP_BASE_DN'); // 'CN=Users,DC=DOMAIN,DC=local'; // Your LDAP base DN
    }

    public function attemptLogin($username, $password)
    {

        $ldap_host =  config('ldapconnection.LDAP_HOST'); // 'ldap://LDAP_SERVER'; // Your LDAP server address
        $ldap_dn = config('ldapconnection.LDAP_BASE_DN'); // 'CN=Users,DC=DOMAIN,DC=local'; // Your LDAP base DN

        // Connect to LDAP server
        $ldap = ldap_connect($ldap_host);


        if (!$ldap) {
            throw new DomainException(
                "LDAP connection failed!."
            );
        }

        // Set LDAP options
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
        // Bind to LDAP server
        $the_username = $username.'@dfcugroup.com';
        $ldap_bind = @ldap_bind($ldap, $the_username, $password);
        if (!$ldap_bind) {
            // Close LDAP connection
           // ldap_close($ldap);
	    Log::info('Not Bound', [ldap_error($ldap)]);
            throw new DomainException(
                "LDAP bind failed!."
            );
        }
        // LDAP search filter
        $filter = "(sAMAccountName=".$username.")"; // Change USERNAME to the user's actual username

        // LDAP search
	$result = ldap_search($ldap, $ldap_dn, $filter);
        if (!$result) {
            // Close LDAP connection
            ldap_close($ldap);
            throw new DomainException(
                "LDAP search failed!."
            );
        }
        // Get entries
        $entries = ldap_get_entries($ldap, $result);
        if ($entries['count'] > 0) {
            // User found
            $user_dn = $entries[0]['dn'];
            // Authenticate user
            //$ldap_bind_user = ldap_bind($ldap, $user_dn, $password);
            if ($ldap_bind) {
                // Close LDAP connection
                ldap_close($ldap);
                return true;
            } else {
                // Close LDAP connection
                ldap_close($ldap);
                throw new DomainException(
                    "User Authentication Failed"
                );
            }
        } else {
            ldap_close($ldap);
            // User not found
            throw new DomainException(
                "User not found."
            );
        }

    }
}

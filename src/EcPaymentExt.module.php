<?php
#BEGIN_LICENSE
#-------------------------------------------------------------------------
# Module: EcPaymentExt (c) 2023 by CMS Made Simple Foundation
#  An addon module for CMS Made Simple to provide a skeleton module that
#  can be used to build payment gateways.
#-------------------------------------------------------------------------
# A fork of:
#
# Module: CGPaymentGatewayBase (c) 2009-2017 by Robert Campbell
#         (calguy1000@cmsmadesimple.org)
#
#-------------------------------------------------------------------------
#
# CMSMS - CMS Made Simple is (c) 2006 - 2023 by CMS Made Simple Foundation
# CMSMS - CMS Made Simple is (c) 2005 by Ted Kulp (wishy@cmsmadesimple.org)
# Visit the CMSMS Homepage at: http://www.cmsmadesimple.org
#
#-------------------------------------------------------------------------
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# However, as a special exception to the GPL, this software is distributed
# as an addon module to CMS Made Simple.  You may not use this software
# in any Non GPL version of CMS Made simple, or in any version of CMS
# Made simple that does not indicate clearly and obviously in its admin
# section that the site was built with CMS Made simple.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
#
#-------------------------------------------------------------------------
#END_LICENSE

if (!class_exists('\EcommerceExt\EcommModule'))
{
    // FIXME
    //$mod = \cms_utils::get_module('EcommerceExt');
    //$mod->autoload('EcommModule');
    include_once cms_join_path( __DIR__ , '../EcommerceExt/lib' , 'class.EcommModule.php');
}

use \EcommerceExt\Payment;

/**
 *
 * The definition for the Payment Gateway base class.
 *
 * @package Calguy
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @category Ecommerce
 * @copyright Copyright 2010 - Robert Campbell
 */

/**
 * An indicator for an approved payment.
 */
define('EcommerceExt\PAYMENT_STATUS_APPROVED','payment_approved');

/**
 * An indicator for a declined payment.
 */
define('EcommerceExt\PAYMENT_STATUS_DECLINED','payment_declined');

/**
 * An indicator for a payment with some kind of error.
 */
define('EcommerceExt\PAYMENT_STATUS_ERROR','payment_error');

/**
 * An indicator for a cancelled payment.
 */
define('EcommerceExt\PAYMENT_STATUS_CANCELLED','payment_cancelled');

/**
 * Some other type of payment time.
 */
define('EcommerceExt\PAYMENT_STATUS_OTHER','payment_other');

/**
 * A notification that payment is pending (gateway has received information about it) but processing is not complete at this time.
 */
define('EcommerceExt\PAYMENT_STATUS_PENDING','payment_pending');

/**
 * A notification that payment has been authorized, funds are probably holding, but needs to be captured before any transfer can occur';
 */
define('EcommerceExt\PAYMENT_STATUS_AUTHORIZED','payment_authorized');

/**
 * An indication that no payment should be registered.
 */
define('EcommerceExt\PAYMENT_STATUS_NONE','payment_none');

/**
 * The module class for the base gateway.
 *
 * This module acts as a base class for payment gateway modules that may provide their own
 * admin interfaces.
 *
 * This module requires CMSMSExt
 * @see CMSMSExt
 * @package Calguy
 * @category Ecommerce
 * @copyright Copyright 2010 - Robert Campbell
 */
class EcPaymentExt extends \EcommerceExt\EcommModule
{
    private $_stub;

    public function GetFriendlyName() {return $this->Lang('friendlyname');}
    public function GetVersion() {return '0.98.0';}
    public function GetAuthor() {return 'Christian Kvikant';}
    public function GetAuthorEmail() {return 'kvide@kvikant.fi';}
    public function IsPluginModule() {return TRUE;}
    public function HasAdmin() {return FALSE;}
    public function LazyLoadAdmin() {return TRUE;}
    public function GetAdminSection() {return 'extensions';}
    public function GetAdminDescription() {return $this->Lang('moddescription');}
    public function VisibleToAdminUser() {return false;}
    public function GetDependencies() {return array('CMSMSExt'=>'1.4.5', 'EcommerceExt'=>'0.98.0');}
    public function MinimumCMSVersion() {return "2.2.19";}

    public function InstallPostMessage() {return $this->Lang('postinstall');}
    public function UninstallPostMessage() {return $this->Lang('postuninstall');}
    public function UninstallPreMessage() {return $this->Lang('really_uninstall');}

    public function SetParameters() {$this->RestrictUnknownParams();}

    public function GetEventDescription($eventname)
    {
        return $this->Lang('desc_event_' . $eventname);
    }

    public function GetEventHelp($eventname)
    {
        return $this->Lang('help_event_' . $eventname);
    }

    /**
     * Get a new gateway object
     *
     * @abstract
     * @return \EcommerceExt\Payment\payment_gateway
     */
    protected function get_new_gateway()
    {
        die('this method must return an object that uses the \EcPaymentExt\payment_gateway interface');
    }

    /**
     * Get the gateway object
     *
     * @abstract
     * @return \EcommerceExt\Payment\payment_gateway
     */
    final protected function get_gateway_obj()
    {
        if (!is_object($this->_stub))
        {
            $this->_stub = $this->get_new_gateway();
            if (!$this->_stub || !$this->_stub instanceof \EcommerceExt\Payment\payment_gateway)
            {
                throw new \LogicException('Gateway provided is not derived from \EcPaymentExt\payment_gateway');
            }
        }

        return $this->_stub;
    }

    public function __call($method, $arguments)
    {
        return call_user_func_array(array($this->get_gateway_obj(), $method), $arguments);
    }

    /**
     * Test if this module has certain capabilities.
     *
     * Payment Gatway developers probably don't need to override this method.
     *
     * @ignore
     */
    public function HasCapability($capability, $params=array())
    {
        // don't include the base module, it's not a real payment gateway
        if ($this->GetName() == 'EcPaymentExt')
        {
            return false;
        }
        // only handle payment gateway capabilties
        if ($capability != 'payment_gateway')
        {
            return false;
        }
        // if this protocol version isn't specified
        // its an error
        if (!isset($params['baseversion']))
        {
            return false;
        }
        $protocol_ver = $params['baseversion'];
        // check to make sure that this module depends on a compatible
        // version of the EcPaymentExt module.
        //$paymw = $this->GetModuleInstance('EcPaymentExt');

        $deps = $this->GetDependencies();
        if (!is_array($deps))
        {
            return false;
        }
        $fnd = false;
        foreach ($deps as $module => $version)
        {
            if ($module != 'EcPaymentExt')
            {
                continue;
            }

            $fnd = true;
            if (version_compare($version, $protocol_ver) < 0)
            {
                audit('', $this->GetName(), 'Not compatible with payment gateway protocol ' . $protocol_ver);
                return false;
            }
        }
        // this should never happen, but hey.
        if (!$fnd)
        {
            return false;
        }

        // all checks passed
        return true;
    }


    //////////////////////////////////////////////////
    // Begin methods required for payment gateways
    //////////////////////////////////////////////////


     /**
      * This method is used to send notification to the order processor that transaction
      * information has arrived asyncrhonously.
      *
      * This method prepares some information, and sends the EcPaymentExt::on_incoming_event event.
      *
      * @deprecated.
      * @final
      * @return void
      */
     protected function ProcessAsyncTransaction($order_id, $transaction_id, $payment_status, $amount, $message = '')
     {
         $transaction = new Payment\async_transaction($order_id, $amount);
         $transaction->set_message($message);
         $transaction->set_id($transaction_id);
         $transaction->set_gateway($this->GetName());
         $transaction->set_status($payment_status);
         $this->send_transaction_notification($transaction);
     }

     /**
      * This method is used to send notification to the order processor that transaction
      * information has arrived asyncrhonously.
      *
      * This method prepares some information, and sends the EcPaymentExt::on_incoming_event event.
      *
      * @final
      * @return void
      */
     final public function send_transaction_notification(Payment\async_transaction& $transaction)
     {
         $parms = array();
         $parms['gateway'] = $this->GetFriendlyName();
         $parms['src_module'] = $this->GetName();
         $order_id = $transaction->get_order_id();
         if ($order_id)
         {
             $parms['order_id'] = $order_id;
         }
         else
         {
             $parms['invoice'] = $transaction->get_invoice();
         }
         $parms['payment_status'] = $transaction->get_status();
         $parms['amount'] = $transaction->get_amount();
         $parms['transaction'] = $transaction->get_id();
         $parms['message'] = $transaction->get_message();

         $parms['event_date'] = time();
         $parms['transaction_obj'] =& $transaction;

         Events::SendEvent('EcPaymentExt', 'on_incoming_event', $parms);
     }

     /**
      * This method is used to test the currently supplied order object to see if there is at least
      * one subscription in the items.
      *
      * @final
      * @since 1.0.10
      * @returns the first subscription item found... or NULL
      */
     final protected function order_contains_subscription()
     {
         // find an item with a subscription
         $order = $this->get_order_object();
         if (is_object($order))
         {
             for ($i = 0; $i < $order->count_destinations(); $i++)
             {
                 $shipping = $order->get_shipping($i);
                 for ($j = 0; $j < $shipping->count_all_items(); $j++)
                 {
                     $item = $shipping->get_item($j);
                     if ($item->is_subscription())
                         return $item;
                 }
             }
         }

         return FALSE;
     }

     /**
      * Get the URL to use for the image for the gateway.
      * The image may be used during the checkout process when displaying a list of possible payment methods.
      *
      * Modules may override this to set a custom image.
      *
      * @since 1.1
      * @returns string
      */
     public function get_image_url()
     {
         $fn = $this->GetModulePath() . '/images/icon.gif';
         if (file_exists($fn))
         {
             return $this->GetModuleURLPath() . '/images/icon.gif';
         }
     }

     /**
     protected function SetOfflineToken($key,$val)
     {
         $key = $this->GetName().'::'.$key;
         Payment\offline_charge::set_token($key,$val);
     }

     protected function GetOfflineToken($key,$uid = null)
     {
         $key = $this->GetName().'::'.$key;
         return Payment\offline_charge::get_token($key,$uid);
     }

     protected function DeleteOfflineToken($key)
     {
         $key = $this->GetName().'::'.$key;
         Payment\offline_charge::delete_token($key);
     }
     **/
} // class

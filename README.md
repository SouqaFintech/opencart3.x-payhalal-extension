# Payhalal OpenCart3.x Extension

*NOTE: You will need to have Opencart installed for this to work.*

## Installation

- Refer to [this](https://github.com/SouqaFintech/opencart-payhalal-extension/wiki) for more detailed installation instructions.

- If you have existing plugin, please backup your Opencart folder first.

- Copy the api key either testing or production by login to your <a href='https://merchant.payhalal.my'>merchant dashboard</a>. Go to General > Developer Tools and view the app key and insert the key in the form shown below in your opencart side for payhalal plugin.

- Your can either enable testing or live depends on your need once you finish doing testing, you can enable the plugin to live mode.

![image](https://payhalal.my/images/plugin_opencart_extensions.jpeg) 

![image](https://payhalal.my/images/plugin_settings.jpeg) 

![image](https://payhalal.my/assets/images/payment-page.jpeg)

After you have activated the plugin and created your Payhalal account, head to the Payhalal Merchant Dashboard and click on Developer tools. Add the following URLs:

Refer to the images below: 

- Login to your <a href='https://merchant.payhalal.my' target='_blank'>merchant dashboard</a>. Then on the left menu click General > Developer Tools, click edit app to insert the url (please refer image below).
![image](https://payhalal.my/images/opencart/developer_tools.jpeg)

- Select which app key that you want to integrate with opencart plugin and insert the URL's as mention below and DO NOT insert callback url for this plugin. Click save button once you finish adding the url's.

![image](https://payhalal.my/images/opencart/url_setting.jpeg)

- Success URL: https://your-website/index.php?route=extension/payment/payhalal/status
- Return URL: https://your-website/index.php?route=extension/payment/payhalal/status
- Cancel URL: https://your-website/index.php?route=extension/payment/payhalal/status
- Callback URL: Please leave this blank to avoid any issues

**Replace "your-website" with your shopping cart domain.**

```bash
git clone https://github.com/SouqaFintech/opencart-payhalal-extension.git
```

## Supported Version

- [x] Opencart version 3.x
- [x] Opencart version 3.x (works on Journal Theme 3.1.8 minimum)

## Note

SouqaFintech SDN BHD **IS NOT RESPONSIBLE** for any problems that may arise from the use of this extension. Use this at your own risk. For any assistance, please email <mark>tech_support@payhalal.my</mark>.

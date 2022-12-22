# Payhalal OpenCart3.x Extension

*NOTE: You will need to have Opencart installed for this to work.*

## Supported Version

- [x] Opencart version 3.x
- [x] Opencart version 3.x (works on Journal Theme 3.1.8 minimum)

## Note

SouqaFintech SDN BHD **IS NOT RESPONSIBLE** for any problems that may arise from the use of this extension. Use this at your own risk. For any assistance, please email <mark>tech_support@payhalal.my</mark>.

## Installation

Refer to [this](https://github.com/SouqaFintech/opencart-payhalal-extension/wiki) for more detailed installation instructions.

If you have existing plugin, please backup your Opencart folder first.

![image](https://payhalal.my/assets/images/plugin-extension.jpeg) 

![image](https://payhalal.my/assets/images/setup-plugin.jpeg) 

![image](https://payhalal.my/assets/images/payment-page.jpeg)

```bash
git clone https://github.com/SouqaFintech/opencart-payhalal-extension.git
```

After you have activated the plugin and created your Payhalal account, head to the Payhalal Merchant Dashboard and click on Developer tools. Add the following URLs:

- Success URL: https://your-website/index.php?route=extension/payment/payhalal/status
- Return URL: https://your-website/index.php?route=extension/payment/payhalal/status
- Cancel URL: https://your-website/index.php?route=extension/payment/payhalal/status




<x-guest-layout>
    <div class="pt-4 bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
            <div>
                <x-authentication-card-logo />
            </div>

            <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg prose dark:prose-invert">
                <x-mary-header title="{{ __('Terms of Service') }}" class="text-gray-800 dark:text-gray-100" />
                <p>{{ __('Welcome to the Stocker app. By using this application, you agree to the following terms and conditions:') }}</p>

                <h2 class="text-gray-800 dark:text-gray-100">{{ __('1. Non-Commercial Use') }}</h2>
                <p>{{ __('This application is for personal use only. It is not intended for commercial use. You may not use this application for any commercial purposes.') }}</p>

                <h2 class="text-gray-800 dark:text-gray-100">{{ __('2. Proprietary Rights') }}</h2>
                <p>{{ __('This application, including all content, features, and functionality, is the property of the owner. You may not replicate, distribute, modify, or create derivative works from this application without explicit permission from the owner.') }}</p>

                <h2 class="text-gray-800 dark:text-gray-100">{{ __('3. Acceptance of Terms') }}</h2>
                <p>{{ __('By using this application, you acknowledge that you have read, understood, and agree to be bound by these terms of use. If you do not agree to these terms, you must stop using the application immediately.') }}</p>

                <h2 class="text-gray-800 dark:text-gray-100">{{ __('4. Data Collection and Processing') }}</h2>
                <p>{{ __('In accordance with the GDPR, we collect and process personal data only for legitimate purposes. By using this app, you agree that we may collect certain personal information, such as your email, usage data, and any other information required to provide our services. For more information on how we handle your personal data, please refer to our') }} <a class="text-emerald-900" href="/privacy-policy">{{ __('Privacy Policy') }}</a>.</p>

                <h2 class="text-gray-800 dark:text-gray-100">{{ __('5. User Rights (GDPR Compliance)') }}</h2>
                <p>{{ __('If you are a resident of the European Union, you have the following rights under the GDPR:') }}
                <ul>
                    <li>{{ __('The right to access your personal data') }}</li>
                    <li>{{ __('The right to correct or update inaccurate data') }}</li>
                    <li>{{ __('The right to request deletion of your data ("right to be forgotten")') }}</li>
                    <li>{{ __('The right to restrict the processing of your data') }}</li>
                    <li>{{ __('The right to data portability (obtain a copy of your data in a structured, commonly used format)') }}</li>
                    <li>{{ __('The right to object to the processing of your personal data under certain circumstances') }}</li>
                </ul>
                <p>{{ __('To exercise any of these rights, please contact us at') }} <a class="text-emerald-800" href="mailto:contact@stocker.lzonca.fr">contact@stocker.lzonca.fr</a>.
                </p>

                <h2 class="text-gray-800 dark:text-gray-100">{{ __('6. Data Security') }}</h2>
                <p>{{ __('We are committed to protecting the security of your data and implement appropriate technical and organizational measures to safeguard your personal information. However, no system can be completely secure, and we cannot guarantee the absolute security of your information.') }}</p>

                <h2 class="text-gray-800 dark:text-gray-100">{{ __('7. Limitation of Liability') }}</h2>
                <p>{{ __('While we strive to maintain the reliability and availability of the Stocker app, we cannot be held liable for any direct or indirect damages resulting from the use or inability to use the app, including but not limited to data loss, system errors, or unauthorized access.') }}</p>

                <h2 class="text-gray-800 dark:text-gray-100">{{ __('8. Modifications to the Terms') }}</h2>
                <p>{{ __('We reserve the right to update these terms of use at any time to reflect changes in our practices or legal obligations. When we make changes, we will notify you by posting the updated terms in the application and revising the "Last Updated" date. Continued use of the application following any changes constitutes your acceptance of the new terms.') }}</p>

                <h2 class="text-gray-800 dark:text-gray-100">{{ __('9. Governing Law') }}</h2>
                <p>{{ __('These terms are governed by and construed in accordance with the laws of the European Union. Any disputes arising from or related to the use of this application will be subject to the jurisdiction of the courts within the EU.') }}</p>

                <h2 class="text-gray-800 dark:text-gray-100">{{ __('10. Contact Information') }}</h2>
                <p>{{ __('If you have any questions about these Terms of Use, or if you wish to exercise your rights under the GDPR, please contact us at') }} <a class="text-emerald-800" href="mailto:contact@stocker.lzonca.fr">contact@stocker.lzonca.fr</a>.</p>
            </div>
        </div>
    </div>
</x-guest-layout>

<x-guest-layout>

    <div class="pt-4 bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
            <div>
                <x-authentication-card-logo />
            </div>

            <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg prose dark:prose-invert">
                <x-mary-header title="{{ __('Privacy Policy') }}" class="text-gray-800 dark:text-gray-100" />
                <p>{{ __('Welcome to the Stocker app. We take your privacy seriously and are committed to protecting your personal data. This privacy policy explains how we collect, use, store, and protect your information when you use our services.') }}</p>

                <h2 class="text-gray-900 dark:text-gray-100">{{ __('1. Data Collection') }}</h2>
                <p>{{ __('We collect personal information that you provide to us directly when you:') }}
                <ul>
                    <li>{{ __('Create an account') }}</li>
                    <li>{{ __('Update your profile') }}</li>
                    <li>{{ __('Use our services or interact with the app') }}</li>
                </ul>
                <p>{{ __('The types of information we collect may include your name, email address, usage data, and any other data you voluntarily provide.') }}
                </p>
                <p>{{ __('We may also automatically collect certain data through cookies or similar technologies, such as device information, IP address, browser type, and app usage statistics.') }}</p>

                <h2 class="text-gray-900 dark:text-gray-100">{{ __('2. How We Use Your Data') }}</h2>
                <p>{{ __('Your personal data is used to:') }}<p>
                    <ul>
                        <li>{{ __('Provide and maintain the functionality of the app') }}</li>
                        <li>{{ __('Personalize your user experience') }}</li>
                        <li>{{ __('Improve and optimize our services') }}</li>
                        <li>{{ __('Communicate with you regarding updates, features, or support') }}</li>
                    </ul>
                   <p> {{ __('We do not sell, share, or use your data for any purposes other than those specified in this policy.') }}</p>

                <h2 class="text-gray-900 dark:text-gray-100">{{ __('3. Data Sharing and Third Parties') }}</h2>
                <p>{{ __('We may share your data with third parties only under the following circumstances:') }}</p>
                <ul>
                    <li>{{ __('To comply with legal obligations (e.g., court orders, subpoenas)') }}</li>
                    <li>{{ __('With trusted service providers who help us operate the app, under strict confidentiality agreements') }}</li>
                    <li>{{ __('To protect the security, integrity, and rights of the app or users') }}</li>
                </ul>
               <p> {{ __('These third parties are only permitted to use your data for the purposes outlined and must adhere to data privacy regulations.') }}
                </p>

                <h2 class="text-gray-900 dark:text-gray-100">{{ __('4. Data Security') }}</h2>
                <p>{{ __('We implement industry-standard security measures to protect your data against unauthorized access, alteration, disclosure, or destruction. This includes encryption, firewalls, and secure server infrastructure.') }}</p>
                <p>{{ __('However, please note that no method of transmission over the internet or method of electronic storage is 100% secure, and we cannot guarantee absolute security.') }}</p>

                <h2 class="text-gray-900 dark:text-gray-100">{{ __('5. Data Retention') }}</h2>
                <p>{{ __('We retain your personal information for as long as necessary to provide our services or comply with legal obligations. If you wish to delete your account or request the removal of your personal data, you may contact us at') }} <a class="text-emerald-900" href="mailto:contact@stocker.lzonca.fr">{{ __('contact@stocker.lzonca.fr') }}</a>, {{ __('and we will handle your request as per applicable data protection laws.') }}</p>

                <h2 class="text-gray-900 dark:text-gray-100">{{ __('6. Your Data Rights') }}</h2>
                <p>{{ __('As a user in the European Union, you have rights under the General Data Protection Regulation (GDPR), including the rights to:') }}
                <ul>
                    <li>{{ __('Access and receive a copy of your personal data') }}</li>
                    <li>{{ __('Rectify inaccurate or incomplete data') }}</li>
                    <li>{{ __('Request erasure of your data under certain circumstances') }}</li>
                    <li>{{ __('Restrict the processing of your data') }}</li>
                    <li>{{ __('Withdraw consent at any time (where consent was required)') }}</li>
                </ul>
                <p>{{ __('To exercise any of these rights, please contact us at') }} <a class="text-emerald-900" href="mailto:contact@stocker.lzonca.fr">{{ __('contact@stocker.lzonca.fr') }}</a>.
                </p>

                <h2 class="text-gray-900 dark:text-gray-100">{{ __('7. International Data Transfers') }}</h2>
                <p>{{ __('Your data may be transferred to and processed in countries outside of the European Economic Area (EEA), which may have different data protection laws than your country. We ensure appropriate safeguards are in place, such as standard contractual clauses or Privacy Shield compliance, to protect your personal information during such transfers.') }}</p>

                <h2 class="text-gray-900 dark:text-gray-100">{{ __('8. Children\'s Privacy') }}</h2>
                <p>{{ __('Our services are not directed to individuals under the age of 13. We do not knowingly collect or solicit personal data from children. If you are a parent or guardian and believe that your child has provided us with personal information, please contact us, and we will promptly delete such data.') }}</p>

                <h2 class="text-gray-900 dark:text-gray-100">{{ __('9. Changes to This Policy') }}</h2>
                <p>{{ __('We may update this privacy policy from time to time to reflect changes in our practices or for legal, operational, or regulatory reasons. When we make changes, we will notify you by updating the policy on our website and changing the date at the top of this document. In some cases, we may also notify you via email or through the app.') }}</p>

                <h2 class="text-gray-900 dark:text-gray-100">{{ __('10. Contact Us') }}</h2>
                <p>{{ __('If you have any questions or concerns about this privacy policy or how we handle your personal information, please contact us at') }} <a class="text-emerald-900" href="mailto:contact@stocker.lzonca.fr">{{ __('contact@stocker.lzonca.fr') }}</a>.</p>
            </div>
        </div>
    </div>
</x-guest-layout>

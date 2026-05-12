<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['donation', 'Are donations to Gopal Seva Samarpan Trust tax exempt?', 'Yes. We are registered under Section 80G of the Income Tax Act. You will receive an 80G receipt by email after every successful donation. PAN is required for receipts above ₹2,000.'],
            ['donation', 'What payment methods do you accept?', 'We accept all major payment methods through Razorpay — credit/debit cards, UPI, net banking, and wallets. You can also donate via direct bank transfer or UPI ID. Bank details are listed on our donation page.'],
            ['donation', 'Can I donate in someone\'s memory or as a gift?', 'Absolutely. On the checkout page, you\'ll find a "Dedication" field where you can mention "in memory of", "in honour of", or any custom message. We can issue a printed acknowledgement for the recipient on request.'],
            ['donation', 'What happens to my money?', 'Every rupee is utilised toward Gau Seva — fodder, medicine, shelter, rescue, and Goshala operations. Less than 3% goes to administration. Annual audited reports are public.'],
            ['donation', 'Can I cancel my monthly donation?', 'Yes, anytime. Email us at seva@gopalsevatrust.org or message on WhatsApp. There is no penalty or commitment.'],

            ['volunteer', 'I don\'t live in Vrindavan — can I still volunteer?', 'Yes. We have remote volunteer opportunities across social media, fundraising, content writing, and CSR outreach.'],
            ['volunteer', 'How much time do I need to commit?', 'There is no minimum commitment. Even a few hours a month can make a real difference.'],
            ['volunteer', 'Are you offering internships?', 'Yes. We accept interns in veterinary studies, communications, social work, and accounting. Email volunteer@gopalsevatrust.org with your CV.'],

            ['visit', 'When is the Goshala open for visitors?', 'Every day from 6:00 AM to 7:00 PM. Morning aarti is at 5:30 AM and evening aarti at 6:30 PM — visitors are warmly welcomed.'],
            ['visit', 'Do I need an appointment?', 'For individual visits, no appointment is needed. For group visits (10+) please email or WhatsApp us 48 hours in advance.'],

            ['tax', 'How do I get my 80G receipt?', 'A digital 80G receipt is automatically emailed to you within 24 hours of a successful donation. You can also request a printed copy.'],
            ['tax', 'Is my donation eligible for 100% tax exemption?', 'Donations qualify for 50% deduction under Section 80G. Specific government-listed projects may qualify for 100% — please contact us for details.'],
        ];

        foreach ($faqs as $i => [$group, $q, $a]) {
            Faq::updateOrCreate(
                ['question' => $q],
                ['group' => $group, 'answer' => $a, 'is_published' => true, 'sort_order' => $i],
            );
        }
    }
}

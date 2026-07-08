<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use Carbon\Carbon;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        // Get or create users
        $users = User::whereHas("roles", function ($query) {
            $query->where("name", "user");
        })
            ->take(5)
            ->get();

        if ($users->count() < 3) {
            // Create some sample users if not enough exist
            for ($i = $users->count(); $i < 3; $i++) {
                $user = new User([
                    "name" => "Customer " . ($i + 1),
                    "email" => "customer" . ($i + 1) . "@example.com",
                    "password" => bcrypt("password"),
                    "is_active" => true,
                    "last_seen_at" => now()->subMinutes(rand(1, 60)),
                    "bio" =>
                        "Pelanggan setia Picia Bakery yang suka kue dan roti.",
                    "phone" => "08123456789" . ($i + 1),
                    "address" => "Jl. Contoh No. " . ($i + 1) . ", Jakarta",
                    "gender" => ["male", "female"][rand(0, 1)],
                    "birth_date" => Carbon::now()
                        ->subYears(rand(20, 50))
                        ->subDays(rand(1, 365)),
                ]);
                $user->save();
                $user->assignRole("user");
                $users->push($user);
            }
        }

        // Sample conversation subjects
        $subjects = [
            "Pertanyaan tentang Pesanan #1001",
            "Keluhan Kualitas Produk",
            "Request Custom Cake untuk Ulang Tahun",
            "Pertanyaan Jam Operasional",
            "Masalah Pembayaran",
            "Complain Keterlambatan Pengiriman",
            "Pertanyaan Menu Baru",
            "Request Katalog Produk",
            "Konsultasi Diet Khusus",
            "Feedback Pelayanan",
        ];

        // Sample messages for different conversation scenarios
        $messageTemplates = [
            "customer_inquiry" => [
                "Selamat pagi, saya ingin bertanya tentang produk terbaru.",
                "Apakah ada diskon untuk pembelian dalam jumlah besar?",
                "Bisakah saya pesan kue custom untuk acara besok?",
                "Saya mau complain nih, pesanan saya terlambat.",
                "Tolong info harga wedding cake dong.",
            ],
            "admin_response" => [
                "Selamat pagi! Terima kasih sudah menghubungi kami.",
                "Baik, akan saya bantu. Mohon tunggu sebentar ya.",
                "Untuk informasi lebih lanjut, bisa saya minta nomor pesanannya?",
                "Maaf atas ketidaknyamanannya. Akan segera kami proses.",
                "Tentu saja! Kami akan buatkan penawaran khusus untuk Anda.",
                "Terima kasih atas feedbacknya. Kami akan tingkatkan pelayanan.",
                "Apakah ada yang bisa saya bantu lagi?",
            ],
            "follow_up" => [
                "Baik, terima kasih infonya.",
                "Oke, saya tunggu ya.",
                "Wah mantap, langsung saya order.",
                "Siap, nanti saya hubungi lagi kalau ada pertanyaan.",
                "Thank you untuk pelayanannya!",
                "Sangat membantu, terima kasih banyak.",
            ],
        ];

        // Create conversations
        foreach ($users->take(8) as $index => $user) {
            // Create 1-3 conversations per user
            $conversationCount = rand(1, 3);

            for ($c = 0; $c < $conversationCount; $c++) {
                $conversation = new Conversation([
                    "user_id" => $user->id,
                    "subject" => $subjects[array_rand($subjects)],
                    "status" => ["open", "closed"][rand(0, 1)],
                    "visibility" => "staff",
                    "created_at" => now()->subDays(rand(1, 30)),
                    "updated_at" => now()->subHours(rand(1, 24)),
                ]);
                $conversation->save();

                // Create messages for this conversation
                $messageCount = rand(3, 12);
                $lastMessageTime = $conversation->created_at;

                for ($m = 0; $m < $messageCount; $m++) {
                    $isCustomerMessage = $m % 3 != 1; // Customer sends more messages
                    $messageUser = $isCustomerMessage
                        ? $user
                        : User::whereHas("roles", function ($query) {
                            $query->whereIn("name", ["admin", "operator"]);
                        })->first();

                    if (!$messageUser && !$isCustomerMessage) {
                        continue; // Skip if no admin/operator user exists
                    }

                    // Select appropriate message template
                    if ($isCustomerMessage) {
                        if ($m == 0) {
                            $messageBody =
                                $messageTemplates["customer_inquiry"][
                                    array_rand(
                                        $messageTemplates["customer_inquiry"],
                                    )
                                ];
                        } else {
                            $messageBody =
                                $messageTemplates["follow_up"][
                                    array_rand($messageTemplates["follow_up"])
                                ];
                        }
                    } else {
                        $messageBody =
                            $messageTemplates["admin_response"][
                                array_rand($messageTemplates["admin_response"])
                            ];
                    }

                    $lastMessageTime = $lastMessageTime->addMinutes(
                        rand(5, 180),
                    );

                    $message = new Message([
                        "conversation_id" => $conversation->id,
                        "user_id" => $messageUser->id,
                        "body" => $messageBody,
                        "read_at" =>
                            $m < $messageCount - rand(0, 2)
                                ? $lastMessageTime->addMinutes(rand(1, 30))
                                : null,
                        "created_at" => $lastMessageTime,
                        "updated_at" => $lastMessageTime,
                    ]);
                    $message->save();
                }

                // Update conversation timestamp to match last message
                $conversation->update(["updated_at" => $lastMessageTime]);
            }
        }

        // Create some recent conversations for demo
        $recentUser = $users->first();
        $recentConversation = new Conversation([
            "user_id" => $recentUser->id,
            "subject" => "Demo - Percakapan Aktif",
            "status" => "open",
            "visibility" => "staff",
            "created_at" => now()->subHours(2),
            "updated_at" => now()->subMinutes(5),
        ]);
        $recentConversation->save();

        $recentMessages = [
            [
                "user" => $recentUser,
                "message" =>
                    "Halo, saya mau pesan birthday cake untuk besok. Bisa?",
                "time" => 30,
            ],
            [
                "user" => "admin",
                "message" =>
                    "Halo! Tentu bisa. Untuk berapa orang dan model seperti apa yang diinginkan?",
                "time" => 28,
            ],
            [
                "user" => $recentUser,
                "message" =>
                    'Untuk 20 orang, mau yang chocolate dengan tulisan "Happy Birthday Sarah". Ada foto referensi juga.',
                "time" => 25,
            ],
            [
                "user" => "admin",
                "message" =>
                    "Baik, bisa kirim foto referensinya? Dan untuk pengambilan jam berapa besok?",
                "time" => 20,
            ],
            [
                "user" => $recentUser,
                "message" => "Jam 3 sore ya. Foto menyusul via WA.",
                "time" => 15,
            ],
            [
                "user" => "admin",
                "message" =>
                    "Siap! Estimasi harga Rp 350.000. Kami tunggu foto referensinya ya.",
                "time" => 5,
            ],
        ];

        foreach ($recentMessages as $msgData) {
            $messageUser =
                $msgData["user"] === "admin"
                    ? User::whereHas("roles", function ($query) {
                        $query->whereIn("name", ["admin", "operator"]);
                    })->first()
                    : $msgData["user"];

            if ($messageUser) {
                $message = new Message([
                    "conversation_id" => $recentConversation->id,
                    "user_id" => $messageUser->id,
                    "body" => $msgData["message"],
                    "read_at" =>
                        $msgData["time"] > 10
                            ? now()->subMinutes($msgData["time"] - 5)
                            : null,
                    "created_at" => now()->subMinutes($msgData["time"]),
                    "updated_at" => now()->subMinutes($msgData["time"]),
                ]);
                $message->save();
            }
        }

        $recentConversation->update(["updated_at" => now()->subMinutes(5)]);

        $this->command->info(
            "Chat seeder completed! Created conversations and messages.",
        );
    }
}

package com.mrdeveloper.printshebapayment;

import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Build;
import android.os.Bundle;
import android.telephony.SmsMessage;
import android.util.Log;

import androidx.core.app.NotificationCompat;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;

public class SmsReceiver extends BroadcastReceiver {

    private static final String TAG = "PrintShebaSms";
    private static final String CHANNEL_ID = "printsheba_payments";

    @Override
    public void onReceive(Context context, Intent intent) {
        if (!"android.provider.Telephony.SMS_RECEIVED".equals(intent.getAction())) {
            return;
        }

        SharedPreferences prefs = context.getSharedPreferences("PrintShebaPrefs", Context.MODE_PRIVATE);
        boolean isServiceEnabled = prefs.getBoolean("service_enabled", true);
        if (!isServiceEnabled) {
            Log.d(TAG, "Listener service is disabled in settings. Skipping.");
            return;
        }

        Bundle bundle = intent.getExtras();
        if (bundle == null) return;

        Object[] pdus = (Object[]) bundle.get("pdus");
        String format = bundle.getString("format");

        if (pdus == null || pdus.length == 0) return;

        StringBuilder fullBody = new StringBuilder();
        String sender = "";

        for (Object pdu : pdus) {
            SmsMessage message;
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
                message = SmsMessage.createFromPdu((byte[]) pdu, format);
            } else {
                message = SmsMessage.createFromPdu((byte[]) pdu);
            }

            if (message != null) {
                sender = message.getDisplayOriginatingAddress();
                fullBody.append(message.getMessageBody());
            }
        }

        String messageText = fullBody.toString();
        Log.d(TAG, "SMS received from " + sender + ": " + messageText);

        PaymentParser.ParsedPayment payment = PaymentParser.parse(sender, messageText);

        if (payment.isValid) {
            Log.i(TAG, "✓ VALID PAYMENT DETECTED: " + payment.toString());

            // Save to recent transactions
            saveRecentTransaction(context, payment);

            // Forward to server webhook
            ApiClient.postPayment(context, payment, new ApiClient.ApiCallback() {
                @Override
                public void onSuccess(String response) {
                    Log.i(TAG, "✓ Payment webhook acknowledged by server: " + response);
                    showNotification(context, "✅ পেমেন্ট নিশ্চিত হয়েছে!",
                            payment.method.toUpperCase() + ": ৳" + payment.amount + " (TrxID: " + payment.trxId + ") প্রিন্টারে রিলিজ হয়েছে!");
                    notifyUiUpdate(context);
                }

                @Override
                public void onError(String error) {
                    Log.e(TAG, "✕ Failed to forward payment: " + error);
                    showNotification(context, "⚠️ পেমেন্ট সিঙ্ক ব্যর্থ",
                            "টাকা: ৳" + payment.amount + " (TrxID: " + payment.trxId + ") কিন্তু সার্ভারে পাঠানো যায়নি: " + error);
                    notifyUiUpdate(context);
                }
            });
        }
    }

    private void saveRecentTransaction(Context context, PaymentParser.ParsedPayment payment) {
        SharedPreferences prefs = context.getSharedPreferences("PrintShebaPrefs", Context.MODE_PRIVATE);
        String existing = prefs.getString("transactions_log", "");
        String timestamp = new SimpleDateFormat("hh:mm:ss a, dd MMM", Locale.getDefault()).format(new Date());

        String newEntry = timestamp + " | " + payment.method.toUpperCase() + " | ৳" + payment.amount + " | TrxID: " + payment.trxId + "\n";
        prefs.edit().putString("transactions_log", newEntry + existing).apply();
    }

    private void notifyUiUpdate(Context context) {
        Intent updateIntent = new Intent("com.mrdeveloper.printshebapayment.UPDATE_UI");
        context.sendBroadcast(updateIntent);
    }

    private void showNotification(Context context, String title, String body) {
        NotificationManager nm = (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
        if (nm == null) return;

        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            NotificationChannel channel = new NotificationChannel(
                    CHANNEL_ID,
                    "PrintSheba Payment Notifications",
                    NotificationManager.IMPORTANCE_HIGH
            );
            channel.setDescription("Shows alerts when customer payment is verified");
            nm.createNotificationChannel(channel);
        }

        Intent openIntent = new Intent(context, MainActivity.class);
        PendingIntent pi = PendingIntent.getActivity(context, 0, openIntent,
                PendingIntent.FLAG_UPDATE_CURRENT | (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M ? PendingIntent.FLAG_IMMUTABLE : 0));

        NotificationCompat.Builder builder = new NotificationCompat.Builder(context, CHANNEL_ID)
                .setSmallIcon(android.R.drawable.stat_notify_chat)
                .setContentTitle(title)
                .setContentText(body)
                .setStyle(new NotificationCompat.BigTextStyle().bigText(body))
                .setPriority(NotificationCompat.PRIORITY_HIGH)
                .setAutoCancel(true)
                .setContentIntent(pi);

        nm.notify((int) System.currentTimeMillis(), builder.build());
    }
}

package com.mrdeveloper.printshebapayment;

import android.app.Notification;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.service.notification.NotificationListenerService;
import android.service.notification.StatusBarNotification;
import android.util.Log;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;

/**
 * 100% Safe, Official Android NotificationListenerService.
 * Replaces invasive RECEIVE_SMS and READ_SMS permissions so bKash, Nagad
 * and Google Play Protect never flag the app as malicious or dangerous.
 */
public class PaymentNotificationListenerService extends NotificationListenerService {

    private static final String TAG = "PrintShebaNotif";

    @Override
    public void onListenerConnected() {
        super.onListenerConnected();
        Log.i(TAG, "PrintSheba NotificationListenerService Connected successfully!");
        ApiClient.sendHeartbeat(getApplicationContext(), null);
    }

    @Override
    public void onNotificationPosted(StatusBarNotification sbn) {
        if (sbn == null) return;

        SharedPreferences prefs = getSharedPreferences("PrintShebaPrefs", Context.MODE_PRIVATE);
        boolean isServiceEnabled = prefs.getBoolean("service_enabled", true);
        if (!isServiceEnabled) {
            return;
        }

        Notification notification = sbn.getNotification();
        if (notification == null) return;

        Bundle extras = notification.extras;
        if (extras == null) return;

        CharSequence titleSeq = extras.getCharSequence(Notification.EXTRA_TITLE);
        CharSequence textSeq = extras.getCharSequence(Notification.EXTRA_TEXT);
        CharSequence bigTextSeq = extras.getCharSequence(Notification.EXTRA_BIG_TEXT);

        String title = titleSeq != null ? titleSeq.toString() : "";
        String text = textSeq != null ? textSeq.toString() : "";
        String bigText = bigTextSeq != null ? bigTextSeq.toString() : "";

        // Merge text contents
        String fullMessage = (text + " " + bigText).trim();
        String packageName = sbn.getPackageName() != null ? sbn.getPackageName().toLowerCase() : "";

        // Check if package or sender is related to bKash, Nagad, or default SMS apps
        boolean isFinancialOrSms = packageName.contains("bkash") ||
                packageName.contains("nagad") ||
                packageName.contains("mfs") ||
                packageName.contains("telephony") ||
                packageName.contains("messaging") ||
                packageName.contains("mms") ||
                packageName.contains("google.android.apps.messaging") ||
                title.toLowerCase().contains("bkash") ||
                title.toLowerCase().contains("nagad") ||
                title.contains("16247") ||
                title.contains("16167");

        if (!isFinancialOrSms) {
            // Also check if text has TrxID/TxnID keyword
            if (!fullMessage.toLowerCase().contains("trxid") && !fullMessage.toLowerCase().contains("txnid")) {
                return;
            }
        }

        Log.d(TAG, "Notification received from [" + packageName + "] Title: " + title + " Text: " + fullMessage);

        PaymentParser.ParsedPayment payment = PaymentParser.parse(title, fullMessage);
        if (payment.isValid) {
            Log.i(TAG, "Valid Payment Parsed from Notification: " + payment.toString());

            appendLog(prefs, "📥 নোটিফিকেশন পাওয়া গেছে: " + payment.toString());

            // Post to PrintSheba Cloud Server
            ApiClient.postPayment(getApplicationContext(), payment, new ApiClient.ApiCallback() {
                @Override
                public void onSuccess(String response) {
                    Log.i(TAG, "Payment synced successfully: " + response);
                    appendLog(prefs, "✅ ক্লাউড সিঙ্ক সফল! " + response);
                    notifyUiUpdate();
                }

                @Override
                public void onError(String error) {
                    Log.e(TAG, "Failed to sync payment: " + error);
                    appendLog(prefs, "❌ সিঙ্ক ত্রুটি: " + error);
                    notifyUiUpdate();
                }
            });
        }
    }

    private void appendLog(SharedPreferences prefs, String entry) {
        String time = new SimpleDateFormat("HH:mm:ss", Locale.getDefault()).format(new Date());
        String fullEntry = "[" + time + "] " + entry;

        String oldLogs = prefs.getString("transactions_log", "");
        String newLogs = fullEntry + "\n" + oldLogs;

        if (newLogs.length() > 6000) {
            newLogs = newLogs.substring(0, 6000);
        }

        prefs.edit().putString("transactions_log", newLogs).apply();
    }

    private void notifyUiUpdate() {
        Intent updateIntent = new Intent("com.mrdeveloper.printshebapayment.UPDATE_UI");
        sendBroadcast(updateIntent);
    }
}

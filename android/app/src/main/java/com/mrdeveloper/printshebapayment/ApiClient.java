package com.mrdeveloper.printshebapayment;

import android.content.Context;
import android.content.SharedPreferences;
import android.util.Log;

import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;

public class ApiClient {

    private static final String TAG = "PrintShebaApi";

    public interface ApiCallback {
        void onSuccess(String response);
        void onError(String error);
    }

    public static void postPayment(Context context, PaymentParser.ParsedPayment payment, ApiCallback callback) {
        new Thread(() -> {
            SharedPreferences prefs = context.getSharedPreferences("PrintShebaPrefs", Context.MODE_PRIVATE);
            String serverUrl = prefs.getString("server_url", "http://10.0.2.2:8000");
            String apiToken = prefs.getString("api_token", "");

            if (apiToken.isEmpty()) {
                if (callback != null) callback.onError("শপ API টোকেন সেট করা নেই!");
                return;
            }

            try {
                String endpoint = serverUrl.replaceAll("/+$", "") + "/api/webhook/sms-payment";
                URL url = new URL(endpoint);
                HttpURLConnection conn = (HttpURLConnection) url.openConnection();
                conn.setRequestMethod("POST");
                conn.setRequestProperty("Content-Type", "application/json; charset=UTF-8");
                conn.setRequestProperty("Accept", "application/json");
                conn.setRequestProperty("X-Agent-Token", apiToken);
                conn.setConnectTimeout(10000);
                conn.setReadTimeout(10000);
                conn.setDoOutput(true);

                JSONObject json = new JSONObject();
                json.put("trx_id", payment.trxId);
                json.put("amount", payment.amount);
                json.put("sender_phone", payment.senderPhone != null ? payment.senderPhone : "");
                json.put("method", payment.method != null ? payment.method : "bkash");

                byte[] postData = json.toString().getBytes(StandardCharsets.UTF_8);
                try (OutputStream os = conn.getOutputStream()) {
                    os.write(postData);
                    os.flush();
                }

                int code = conn.getResponseCode();
                BufferedReader br = new BufferedReader(new InputStreamReader(
                        code >= 200 && code < 300 ? conn.getInputStream() : conn.getErrorStream(),
                        StandardCharsets.UTF_8
                ));
                StringBuilder response = new StringBuilder();
                String line;
                while ((line = br.readLine()) != null) {
                    response.append(line);
                }
                br.close();

                Log.d(TAG, "Response [" + code + "]: " + response.toString());

                if (code >= 200 && code < 300) {
                    if (callback != null) callback.onSuccess(response.toString());
                } else {
                    if (callback != null) callback.onError("সার্ভার এরর (" + code + "): " + response.toString());
                }

            } catch (Exception e) {
                Log.e(TAG, "Network error: " + e.getMessage(), e);
                if (callback != null) callback.onError("নেটওয়ার্ক সমস্যা: " + e.getMessage());
            }
        }).start();
    }

    public static void sendHeartbeat(Context context, ApiCallback callback) {
        new Thread(() -> {
            SharedPreferences prefs = context.getSharedPreferences("PrintShebaPrefs", Context.MODE_PRIVATE);
            String serverUrl = prefs.getString("server_url", "http://10.0.2.2:8000");
            String apiToken = prefs.getString("api_token", "");

            if (apiToken.isEmpty()) {
                if (callback != null) callback.onError("টোকেন সেট করা নেই");
                return;
            }

            try {
                String endpoint = serverUrl.replaceAll("/+$", "") + "/api/app/heartbeat";
                URL url = new URL(endpoint);
                HttpURLConnection conn = (HttpURLConnection) url.openConnection();
                conn.setRequestMethod("POST");
                conn.setRequestProperty("Content-Type", "application/json; charset=UTF-8");
                conn.setRequestProperty("Accept", "application/json");
                conn.setRequestProperty("X-Agent-Token", apiToken);
                conn.setConnectTimeout(8000);
                conn.setReadTimeout(8000);
                conn.setDoOutput(true);

                JSONObject json = new JSONObject();
                json.put("app_version", "2.1");
                json.put("device", android.os.Build.MODEL);

                byte[] postData = json.toString().getBytes(StandardCharsets.UTF_8);
                try (OutputStream os = conn.getOutputStream()) {
                    os.write(postData);
                    os.flush();
                }

                int code = conn.getResponseCode();
                if (code >= 200 && code < 300) {
                    if (callback != null) callback.onSuccess("সার্ভারে সফলভাবে কানেক্টেড (OK)");
                } else {
                    if (callback != null) callback.onError("সার্ভার রেসপন্স কোড: " + code);
                }
            } catch (Exception e) {
                if (callback != null) callback.onError("কানেকশন সমস্যা: " + e.getMessage());
            }
        }).start();
    }
}

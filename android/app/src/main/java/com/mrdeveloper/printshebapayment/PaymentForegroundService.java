package com.mrdeveloper.printshebapayment;

import android.app.Notification;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.Service;
import android.content.Context;
import android.content.Intent;
import android.os.Build;
import android.os.IBinder;

import androidx.annotation.Nullable;
import androidx.core.app.NotificationCompat;

public class PaymentForegroundService extends Service {

    private static final String CHANNEL_ID = "printsheba_foreground_service";
    private static final int NOTIF_ID = 1001;

    @Override
    public void onCreate() {
        super.onCreate();
        createNotificationChannel();
    }

    private android.os.Handler handler;
    private Runnable heartbeatRunnable;

    @Override
    public int onStartCommand(Intent intent, int flags, int startId) {
        Intent notificationIntent = new Intent(this, MainActivity.class);
        PendingIntent pendingIntent = PendingIntent.getActivity(
                this,
                0,
                notificationIntent,
                PendingIntent.FLAG_UPDATE_CURRENT | (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M ? PendingIntent.FLAG_IMMUTABLE : 0)
        );

        Notification notification = new NotificationCompat.Builder(this, CHANNEL_ID)
                .setContentTitle("PrintSheba পেমেন্ট লিসেনার সক্রিয়")
                .setContentText("বিকাশ ও নগদ এসএমএস স্বয়ংক্রিয়ভাবে নজরদারিতে রয়েছে...")
                .setSmallIcon(android.R.drawable.stat_notify_sync)
                .setContentIntent(pendingIntent)
                .setOngoing(true)
                .build();

        startForeground(NOTIF_ID, notification);

        // Schedule periodic heartbeat every 45 seconds so dashboard stays live
        if (handler == null) {
            handler = new android.os.Handler(android.os.Looper.getMainLooper());
            heartbeatRunnable = new Runnable() {
                @Override
                public void run() {
                    ApiClient.sendHeartbeat(getApplicationContext(), null);
                    handler.postDelayed(this, 45000);
                }
            };
            handler.post(heartbeatRunnable);
        }

        return START_STICKY;
    }

    @Override
    public void onDestroy() {
        if (handler != null && heartbeatRunnable != null) {
            handler.removeCallbacks(heartbeatRunnable);
        }
        super.onDestroy();
    }

    @Nullable
    @Override
    public IBinder onBind(Intent intent) {
        return null;
    }

    private void createNotificationChannel() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            NotificationChannel serviceChannel = new NotificationChannel(
                    CHANNEL_ID,
                    "PrintSheba 24/7 Background Service",
                    NotificationManager.IMPORTANCE_LOW
            );
            NotificationManager manager = getSystemService(NotificationManager.class);
            if (manager != null) {
                manager.createNotificationChannel(serviceChannel);
            }
        }
    }
}

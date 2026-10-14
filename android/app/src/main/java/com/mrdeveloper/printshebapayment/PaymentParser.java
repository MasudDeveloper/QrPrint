package com.mrdeveloper.printshebapayment;

import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class PaymentParser {

    public static class ParsedPayment {
        public String method; // bkash, nagad, rocket
        public double amount;
        public String senderPhone;
        public String trxId;
        public boolean isValid;

        public ParsedPayment() {
            this.isValid = false;
        }

        @Override
        public String toString() {
            return method.toUpperCase() + ": ৳" + amount + " | TrxID: " + trxId + " | From: " + senderPhone;
        }
    }

    public static ParsedPayment parse(String sender, String messageBody) {
        ParsedPayment payment = new ParsedPayment();
        if (messageBody == null || messageBody.trim().isEmpty()) {
            return payment;
        }

        String msg = messageBody.trim();
        String senderUpper = sender != null ? sender.toUpperCase() : "";

        // 1. Check bKash pattern
        // Example: "You have received Tk 20.00 from 01711223344. Ref . Fee Tk 0.00. Balance Tk 550.00. TrxID BLA892K123 at 12/10/2026 14:30"
        if (senderUpper.contains("BKASH") || senderUpper.contains("16247") || msg.toLowerCase().contains("trxid") && msg.toLowerCase().contains("bkash")) {
            payment.method = "bkash";

            Pattern pAmount = Pattern.compile("(?:Tk|amount:?\\s*Tk)\\s*([0-9,.]+)", Pattern.CASE_INSENSITIVE);
            Matcher mAmount = pAmount.matcher(msg);
            if (mAmount.find()) {
                payment.amount = parseDoubleSafe(mAmount.group(1));
            }

            Pattern pTrx = Pattern.compile("TrxID\\s*[:\\s]?\\s*([A-Za-z0-9]+)", Pattern.CASE_INSENSITIVE);
            Matcher mTrx = pTrx.matcher(msg);
            if (mTrx.find()) {
                payment.trxId = mTrx.group(1).trim().toUpperCase();
            }

            Pattern pPhone = Pattern.compile("(?:from|sender:?)\\s*(01[0-9]{9})", Pattern.CASE_INSENSITIVE);
            Matcher mPhone = pPhone.matcher(msg);
            if (mPhone.find()) {
                payment.senderPhone = mPhone.group(1).trim();
            }

            if (payment.amount > 0 && payment.trxId != null && !payment.trxId.isEmpty()) {
                payment.isValid = true;
                return payment;
            }
        }

        // 2. Check Nagad pattern
        // Example: "Money Received. Amount: Tk 30.00. Sender: 01811223344. TxnID: 72HJ91KL. Balance: Tk 1,200.00."
        if (senderUpper.contains("NAGAD") || senderUpper.contains("16167") || msg.toLowerCase().contains("txnid") && msg.toLowerCase().contains("nagad")) {
            payment.method = "nagad";

            Pattern pAmount = Pattern.compile("(?:Amount:\\s*Tk|Tk)\\s*([0-9,.]+)", Pattern.CASE_INSENSITIVE);
            Matcher mAmount = pAmount.matcher(msg);
            if (mAmount.find()) {
                payment.amount = parseDoubleSafe(mAmount.group(1));
            }

            Pattern pTrx = Pattern.compile("TxnID\\s*[:\\s]?\\s*([A-Za-z0-9]+)", Pattern.CASE_INSENSITIVE);
            Matcher mTrx = pTrx.matcher(msg);
            if (mTrx.find()) {
                payment.trxId = mTrx.group(1).trim().toUpperCase();
            }

            Pattern pPhone = Pattern.compile("(?:Sender:\\s*|from\\s*)(01[0-9]{9})", Pattern.CASE_INSENSITIVE);
            Matcher mPhone = pPhone.matcher(msg);
            if (mPhone.find()) {
                payment.senderPhone = mPhone.group(1).trim();
            }

            if (payment.amount > 0 && payment.trxId != null && !payment.trxId.isEmpty()) {
                payment.isValid = true;
                return payment;
            }
        }

        // 3. Fallback generic SMS with TrxID / TxnID and Amount
        Pattern pGenericTrx = Pattern.compile("(?:TrxID|TxnID)\\s*[:\\s]?\\s*([A-Za-z0-9]+)", Pattern.CASE_INSENSITIVE);
        Matcher mGenericTrx = pGenericTrx.matcher(msg);
        if (mGenericTrx.find()) {
            payment.trxId = mGenericTrx.group(1).trim().toUpperCase();
            payment.method = msg.toLowerCase().contains("nagad") ? "nagad" : "bkash";

            Pattern pGenericAmount = Pattern.compile("(?:Tk|amount)\\s*([0-9,.]+)", Pattern.CASE_INSENSITIVE);
            Matcher mGenericAmount = pGenericAmount.matcher(msg);
            if (mGenericAmount.find()) {
                payment.amount = parseDoubleSafe(mGenericAmount.group(1));
            }

            Pattern pGenericPhone = Pattern.compile("(01[0-9]{9})");
            Matcher mGenericPhone = pGenericPhone.matcher(msg);
            if (mGenericPhone.find()) {
                payment.senderPhone = mGenericPhone.group(1);
            }

            if (payment.amount > 0 && payment.trxId != null) {
                payment.isValid = true;
            }
        }

        return payment;
    }

    private static double parseDoubleSafe(String val) {
        if (val == null) return 0.0;
        try {
            return Double.parseDouble(val.replace(",", "").trim());
        } catch (Exception e) {
            return 0.0;
        }
    }
}

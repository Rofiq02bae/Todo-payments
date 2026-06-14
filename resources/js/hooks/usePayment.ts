import { useState } from "react";

export function usePayment() {
    const [loading, setLoading] = useState(false);

    const exportPdf = async (todoId: number) => {
        try {
            setLoading(true);

            const response = await fetch(`/payments/create/${todoId}`, {
                headers: {
                    Accept: "application/json",
                },
            });

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message);
            }

            window.snap.pay(result.data.snap_token, {
                onSuccess(result) {
                    console.log("Payment Success", result);
                },

                onPending(result) {
                    console.log("Payment Pending", result);
                },

                onError(result) {
                    console.log("Payment Error", result);
                },

                onClose() {
                    console.log("Payment Popup Closed");
                },
            });
        } catch (error) {
            console.error(error);
            alert("Gagal membuat pembayaran.");
        } finally {
            setLoading(false);
        }
    };

    return {
        exportPdf,
        loading,
    };
}
import { useState } from "react";
import { toast } from "sonner";

export function usePayment() {
    const [loading, setLoading] = useState(false);
    const [modalOpen, setModalOpen] = useState(false);
    const [downloadUrl, setDownloadUrl] = useState<string | null>(null);

    const checkAndPay = async (todoId: number) => {
        try {
            setLoading(true);

            // Step 1: Check if todo already has paid PDF
            const checkResponse = await fetch(`/pdf/generate/${todoId}`, {
                method: "POST",
                headers: { Accept: "application/json" },
            });

            const checkResult = await checkResponse.json();

            if (checkResult.needs_payment) {
                // Step 2: Not paid yet — create payment and open Snap
                await createPaymentAndSnap(todoId);
            } else {
                // Step 3: Already paid — show download modal
                setDownloadUrl(checkResult.download_url);
                setModalOpen(true);
            }
        } catch (error) {
            console.error(error);
            toast.error("Terjadi kesalahan. Silakan coba lagi.");
        } finally {
            setLoading(false);
        }
    };

    const createPaymentAndSnap = async (todoId: number) => {
        const response = await fetch(`/payments/create/${todoId}`, {
            headers: { Accept: "application/json" },
        });

        const result = await response.json();

        if (!result.success) {
            throw new Error(result.message);
        }

        if (result.already_paid) {
            await generatePdfAfterPayment(todoId);

            return;
        }

        window.snap.pay(result.data.snap_token, {
            onSuccess: async (snapResult) => {
                toast.success("Pembayaran sukses", {
                    duration: 5000,
                });

                // Pass order_id from Snap result so backend can find the payment
                await generatePdfAfterPayment(todoId, snapResult);
            },

            onPending() {
                toast.info("Pembayaran sedang diproses.");
            },

            onError() {
                toast.error("Pembayaran gagal. Silakan coba lagi.");
            },

            onClose() {
                console.log("Payment popup closed");
            },
        });
    };

    const generatePdfAfterPayment = async (todoId: number, snapResult?: Record<string, unknown>) => {
        try {
            const body: Record<string, unknown> = {};

            if (snapResult?.order_id) {
                body.order_id = snapResult.order_id;
            }

            if (snapResult?.transaction_id) {
                body.transaction_id = snapResult.transaction_id;
            }

            const response = await fetch(`/pdf/generate/${todoId}`, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(body),
            });

            const result = await response.json();

            if (result.download_url) {
                setDownloadUrl(result.download_url);
                setModalOpen(true);
            } else {
                toast.error("Gagal membuat PDF.");
            }
        } catch (error) {
            console.error(error);
            toast.error("Gagal membuat PDF.");
        }
    };

    const closeModal = () => {
        setModalOpen(false);
        setDownloadUrl(null);
    };

    return {
        exportPdf: checkAndPay,
        loading,
        modalOpen,
        downloadUrl,
        closeModal,
    };
}

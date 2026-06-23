import { CircleCheck, X, Download } from "lucide-react";
import { useEffect, useRef } from "react";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from "@/components/ui/card";

type Props = {
    open: boolean;
    downloadUrl: string | null;
    onClose: () => void;
};

export default function PdfDownloadModal({ open, downloadUrl, onClose }: Props) {
    const overlayRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!open) {
return;
}

        const handleKey = (e: KeyboardEvent) => {
            if (e.key === "Escape") {
onClose();
}
        };
        document.addEventListener("keydown", handleKey);

        return () => document.removeEventListener("keydown", handleKey);
    }, [open, onClose]);

    useEffect(() => {
        if (open) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = "";
        }

        return () => {
            document.body.style.overflow = "";
        };
    }, [open]);

    if (!open) {
return null;
}

    const handleOverlayClick = (e: React.MouseEvent) => {
        if (e.target === overlayRef.current) {
onClose();
}
    };

    const handleDownload = () => {
        if (downloadUrl) {
            window.open(downloadUrl, "_blank");
        }
    };

    return (
        <div
            ref={overlayRef}
            onClick={handleOverlayClick}
            className="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
        >
            <Card className="relative mx-4 w-full max-w-sm text-center shadow-lg bg-white">
                <button
                    onClick={onClose}
                    className="absolute right-3 top-3 text-muted-foreground hover:text-foreground"
                    aria-label="Close"
                >
                    <X className="size-5" />
                </button>

                <CardHeader className="pb-2">
                    <div className="flex justify-center">
                        <CircleCheck className="size-16 text-green-500" />
                    </div>
                    <CardTitle className="mt-2 text-lg">Pembayaran Sukses</CardTitle>
                </CardHeader>

                <CardContent className="pb-4">
                    <p className="text-sm text-muted-foreground">
                        Pembayaran Anda telah berhasil diproses.
                        <br />
                        Klik tombol di bawah untuk mengunduh PDF.
                    </p>
                </CardContent>

                <CardFooter className="justify-center pb-6">
                    <Button onClick={handleDownload} disabled={!downloadUrl} className="gap-2">
                        <Download className="size-4" />
                        Download
                    </Button>
                </CardFooter>
            </Card>
        </div>
    );
}

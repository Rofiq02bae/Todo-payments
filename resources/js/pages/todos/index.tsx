import { Head, Link } from "@inertiajs/react";
import { Toaster } from "sonner";

import PdfDownloadModal from "@/components/PdfDownloadModal";
import TodoCard from "@/components/TodoCard";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { usePayment } from "@/hooks/usePayment";

type Todo = {
    id: number;
    title: string;
    description: string;
    is_completed: boolean;
    created_at: string;
    updated_at: string;
};

type Props = {
  todos: Todo[];
};
export default function Index({ todos }: Props) {
  const { exportPdf, loading, modalOpen, downloadUrl, closeModal } = usePayment();

  return (
    <>
      <Head>
        <title>Todo List</title>
      </Head>

      <Toaster
        position="top-right"
        richColors
        closeButton
      />

      <PdfDownloadModal
        open={modalOpen}
        downloadUrl={downloadUrl}
        onClose={closeModal}
      />

        <h1 className="text-2xl font-bold mb-4">Todo List</h1>
      <div className="container flex flex-col items-center justify-center gap-6 px-4 py-8">
        <Card className="w-full max-w-md">
          <CardHeader>
            <CardTitle>Todo List</CardTitle>
            <CardDescription>Manage your tasks effectively.</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="mb-4 flex flex-col gap-3">
              {todos.length > 0 ? (
                todos.map((todo) => (
                  <TodoCard key={todo.id} todo={todo} onExport={exportPdf} loading={loading} />
                ))
              ) : (
                <p className="text-sm text-muted-foreground">
                  No tasks yet.
                </p>
              )}
            </div>
            
            <Button variant="outline" className="w-full" asChild>
              <Link href="/todos/create">
                Add New Task
              </Link>
            </Button>
            
          </CardContent>
        </Card>
      </div>    
    </>
  );
}

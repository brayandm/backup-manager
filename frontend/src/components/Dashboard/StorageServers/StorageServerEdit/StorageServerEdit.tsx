"use client";

interface StorageServerEditProps {
  id: string;
  render: boolean;
  setRender: React.Dispatch<React.SetStateAction<boolean>>;
}

function StorageServerEdit({ id, render, setRender }: StorageServerEditProps) {
  const handleGoBack = () => {
    const searchParams = new URLSearchParams(window.location.search);
    searchParams.delete("option");
    searchParams.delete("id");
    window.history.replaceState(
      {},
      "",
      `${window.location.pathname}?${searchParams}`
    );
    setRender(!render);
  };

  return (
    <>
      <button onClick={handleGoBack}>Back</button>
      <p>Edit {id}</p>
    </>
  );
}

export default StorageServerEdit;

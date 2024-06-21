"use client";

interface BackupConfigurationEditProps {
  id: string;
  render: boolean;
  setRender: React.Dispatch<React.SetStateAction<boolean>>;
}

function BackupConfigurationEdit({
  id,
  render,
  setRender,
}: BackupConfigurationEditProps) {
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

export default BackupConfigurationEdit;

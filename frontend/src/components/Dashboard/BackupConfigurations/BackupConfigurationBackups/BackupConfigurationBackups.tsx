"use client";

import React from "react";

interface BackupConfigurationBackupsProps {
  id: string;
  render: boolean;
  setRender: React.Dispatch<React.SetStateAction<boolean>>;
}

function BackupConfigurationBackups({
  id,
  render,
  setRender,
}: BackupConfigurationBackupsProps) {
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
      <p>Backups for Backup Configuration {id}</p>
    </>
  );
}

export default BackupConfigurationBackups;

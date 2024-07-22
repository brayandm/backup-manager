"use client";

import React, { useEffect, useState } from "react";
import BackupConfigurationView from "./BackupConfigurationView";
import BackupConfigurationBackups from "./BackupConfigurationBackups";
import BackupConfigurationForm from "./BackupConfigurationForm";

interface BackupConfigurationsProps {}

function BackupConfigurations({}: BackupConfigurationsProps) {
  const [render, setRender] = React.useState(false);
  const [option, setOption] = useState<string | null>(null);
  const [id, setId] = useState<string | null>(null);

  useEffect(() => {
    const queryParams = new URLSearchParams(window.location.search);
    const option = queryParams.get("option");
    const id = queryParams.get("id");
    setOption(option);
    setId(id);
  }, [render]);

  if (option === "create") {
    return <BackupConfigurationForm render={render} setRender={setRender} />;
  }

  if (option === "edit") {
    return (
      <BackupConfigurationForm id={id!} render={render} setRender={setRender} />
    );
  }

  if (option === "backups") {
    return (
      <BackupConfigurationBackups
        id={id!}
        render={render}
        setRender={setRender}
      />
    );
  }

  return <BackupConfigurationView render={render} setRender={setRender} />;
}

export default BackupConfigurations;

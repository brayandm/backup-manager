"use client";

import React, { useEffect, useState } from "react";
import BackupConfigurationView from "./BackupConfigurationView";
import BackupConfigurationCreate from "./BackupConfigurationCreate";
import BackupConfigurationEdit from "./BackupConfigurationEdit";
import BackupConfigurationBackups from "./BackupConfigurationBackups";

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
    return <BackupConfigurationCreate render={render} setRender={setRender} />;
  }

  if (option === "edit") {
    return (
      <BackupConfigurationEdit id={id!} render={render} setRender={setRender} />
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

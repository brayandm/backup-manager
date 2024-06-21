"use client";

import React, { useEffect, useState } from "react";
import BackupConfigurationView from "./BackupConfigurationView";
import BackupConfigurationCreate from "./BackupConfigurationCreate";

interface BackupConfigurationsProps {}

function BackupConfigurations({}: BackupConfigurationsProps) {
  const [render, setRender] = React.useState(false);
  const [option, setOption] = useState<string | null>(null);

  useEffect(() => {
    const queryParams = new URLSearchParams(window.location.search);
    const option = queryParams.get("option");
    setOption(option);
  }, [render]);

  if (option === "create") {
    return <BackupConfigurationCreate />;
  }

  return <BackupConfigurationView render={render} setRender={setRender} />;
}

export default BackupConfigurations;

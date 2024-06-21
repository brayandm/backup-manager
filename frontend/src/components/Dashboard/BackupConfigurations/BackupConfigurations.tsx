"use client";

import React, { useEffect, useState } from "react";
import BackupConfigurationView from "./BackupConfigurationView";

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
    return <p>Create</p>;
  }

  return <BackupConfigurationView render={render} setRender={setRender} />;
}

export default BackupConfigurations;

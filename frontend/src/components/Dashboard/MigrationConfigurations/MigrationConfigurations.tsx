"use client";

import React, { useEffect, useState } from "react";
import MigrationConfigurationView from "./MigrationConfigurationView";
// import MigrationConfigurationForm from "./MigrationConfigurationForm";

interface MigrationConfigurationsProps {}

function MigrationConfigurations({}: MigrationConfigurationsProps) {
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

  // if (option === "create") {
  //   return <MigrationConfigurationForm render={render} setRender={setRender} />;
  // }

  // if (option === "edit") {
  //   return (
  //     <MigrationConfigurationForm
  //       id={id!}
  //       render={render}
  //       setRender={setRender}
  //     />
  //   );
  // }

  return <MigrationConfigurationView render={render} setRender={setRender} />;
}

export default MigrationConfigurations;

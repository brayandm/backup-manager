"use client";

import TabSection from "@/components/TabSection";
import ArrowBackIcon from "@mui/icons-material/ArrowBack";
import { Alert, Button, IconButton, Tooltip } from "@mui/material";
import { useEffect, useState } from "react";
import { post, get, put } from "@/lib/backendApi";
import MigrationConfigurationBasicForm from "@/components/MigrationConfigurationBasicForm";
import MigrationConfigurationScheduleForm from "@/components/MigrationConfigurationScheduleForm";
import MigrationConfigurationAdvancedForm from "@/components/MigrationConfigurationAdvancedForm";
import EditNoteIcon from "@mui/icons-material/EditNote";
import AddIcon from "@mui/icons-material/Add";

interface MigrationConfigurationFormProps {
  id?: string;
  render: boolean;
  setRender: React.Dispatch<React.SetStateAction<boolean>>;
}

function MigrationConfigurationForm({
  id,
  render,
  setRender,
}: MigrationConfigurationFormProps) {
  const handleGoBack = () => {
    const searchParams = new URLSearchParams(window.location.search);
    searchParams.delete("option");
    window.history.replaceState(
      {},
      "",
      `${window.location.pathname}?${searchParams}`
    );
    setRender(!render);
  };

  const [dataSourceNames, setDataSourceNames] = useState<
    {
      id: number;
      name: string;
    }[]
  >([]);

  useEffect(() => {
    const fetchDataSource = async () => {
      const res = await get("/data-sources/names");
      if (res.status === 200) {
        const data = (await res) as {
          data: {
            id: number;
            name: string;
          }[];
        };

        setDataSourceNames(data.data);
      }
    };
    fetchDataSource();
  }, []);

  const [name, setName] = useState("");
  const [dataSources, setDataSources] = useState<
    {
      id: number;
      name: string;
    }[]
  >([]);
  const [storageServers, setStorageServers] = useState<
    {
      id: number;
      name: string;
    }[]
  >([]);
  const [scheduleCron, setScheduleCron] = useState("0 0 * * *");
  const [manualMigration, setManualMigration] = useState(false);
  const [compression, setCompression] = useState('{"type": "tar"}');

  useEffect(() => {
    if (id) {
      const fetchMigrationConfiguration = async () => {
        const res = await get("/migration-configurations/show/" + id);
        if (res.status === 200) {
          const data = (await res) as {
            data: {
              name: string;
              data_sources: { id: number; name: string }[];
              storage_servers: { id: number; name: string }[];
              connection_config: string;
              driver_config: string;
              schedule_cron: string;
              manual_migration: boolean;
              retention_policy_config: string;
              compression_config: string;
              encryption_config: string;
              integrity_check_config: string;
            };
          };

          setName(data.data.name);
          setDataSources(data.data.data_sources);
          setStorageServers(data.data.storage_servers);
          setScheduleCron(data.data.schedule_cron);
          setManualMigration(data.data.manual_migration);
          setCompression(data.data.compression_config);
        }
      };
      fetchMigrationConfiguration();
    }
  }, [id]);

  const [basicTabMissingValues, setBasicTabMissingValues] = useState(
    id ? false : true
  );
  const [scheduleTabMissingValues, setScheduleTabMissingValues] =
    useState(false);
  const [advancedTabMissingValues, setAdvancedTabMissingValues] =
    useState(false);

  const missingValues =
    basicTabMissingValues ||
    scheduleTabMissingValues ||
    advancedTabMissingValues;

  const [onError, setOnError] = useState(false);
  const [onSuccess, setOnSuccess] = useState(false);

  return (
    <div style={{ position: "relative" }}>
      {onError && (
        <div
          style={{
            position: "absolute",
            display: "flex",
            justifyContent: "center",
            alignItems: "center",
            width: "300px",
            top: "-4vh",
            left: "calc(50% - 150px)",
            zIndex: 1,
          }}
        >
          <Alert severity="error"> Missing values </Alert>
        </div>
      )}
      {onSuccess && (
        <div
          style={{
            position: "absolute",
            display: "flex",
            justifyContent: "center",
            alignItems: "center",
            width: "300px",
            top: "-4vh",
            left: "calc(50% - 150px)",
            zIndex: 1,
          }}
        >
          <Alert severity="success">
            {" "}
            Migration Configuration {id ? "Update" : "Create"}
          </Alert>
        </div>
      )}
      <div
        style={{
          position: "absolute",
          display: "flex",
          justifyContent: "center",
          alignItems: "center",
          top: "0",
          left: "0",
          zIndex: 1,
          width: 80,
          height: 80,
        }}
      >
        <Tooltip title="Back" placement="right-start">
          <IconButton aria-label="back" onClick={handleGoBack}>
            <ArrowBackIcon fontSize="inherit" />
          </IconButton>
        </Tooltip>
      </div>
      <div
        style={{
          position: "absolute",
          display: "flex",
          justifyContent: "center",
          alignItems: "center",
          bottom: "40px",
          right: "40px",
          zIndex: 1,
        }}
      >
        <Button
          variant="contained"
          endIcon={id ? <EditNoteIcon /> : <AddIcon />}
          onClick={async () => {
            if (missingValues) {
              setOnError(true);
              setTimeout(() => {
                setOnError(false);
              }, 2000);
            } else {
              const res = id
                ? await put("/migration-configurations/update/" + id, {
                    name: name,
                    data_source_ids: dataSources.map((source) => source.id),
                    storage_server_ids: storageServers.map(
                      (server) => server.id
                    ),
                    schedule_cron: scheduleCron,
                    manual_migration: manualMigration,
                    compression_config: compression,
                  })
                : await post("/migration-configurations/store", {
                    name: name,
                    data_source_ids: dataSources.map((source) => source.id),
                    storage_server_ids: storageServers.map(
                      (server) => server.id
                    ),
                    schedule_cron: scheduleCron,
                    manual_migration: manualMigration,
                    compression_config: compression,
                  });

              if (res.status === (id ? 200 : 201)) {
                setOnSuccess(true);
                setTimeout(() => {
                  setOnSuccess(false);
                  const searchParams = new URLSearchParams(
                    window.location.search
                  );
                  searchParams.delete("option");
                  window.history.replaceState(
                    {},
                    "",
                    `${window.location.pathname}?${searchParams}`
                  );
                  setRender(!render);
                }, 2000);
              }
            }
          }}
          disabled={missingValues}
        >
          {id ? "Update" : "Create"}
        </Button>
      </div>
      <TabSection
        tabs={[
          // {
          //   missingValues: basicTabMissingValues,
          //   label: "Basic",
          //   component: (
          //     <MigrationConfigurationBasicForm
          //       dataSourceNames={dataSourceNames}
          //       dataSources={dataSources}
          //       setDataSources={setDataSources}
          //       storageServerNames={storageServerNames}
          //       storageServers={storageServers}
          //       setStorageServers={setStorageServers}
          //       name={name}
          //       setName={setName}
          //       setMissingValues={setBasicTabMissingValues}
          //     />
          //   ),
          // },
          {
            missingValues: scheduleTabMissingValues,
            label: "Schedule",
            component: (
              <MigrationConfigurationScheduleForm
                scheduleCron={scheduleCron}
                setScheduleCron={setScheduleCron}
                manualMigration={manualMigration}
                setManualMigration={setManualMigration}
                setMissingValues={setScheduleTabMissingValues}
              />
            ),
          },
          {
            missingValues: advancedTabMissingValues,
            label: "Advanced",
            component: (
              <MigrationConfigurationAdvancedForm
                compression={compression}
                setCompression={setCompression}
                setMissingValues={setAdvancedTabMissingValues}
              />
            ),
          },
        ]}
      />
    </div>
  );
}

export default MigrationConfigurationForm;

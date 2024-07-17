"use client";

import TabSection from "@/components/TabSection";
import ArrowBackIcon from "@mui/icons-material/ArrowBack";
import { Alert, Button, IconButton, Tooltip } from "@mui/material";
import AddIcon from "@mui/icons-material/Add";
import { useEffect, useState } from "react";
import { get, post } from "@/lib/backendApi";
import BackupConfigurationBasicForm from "@/components/BackupConfigurationBasicForm";
import BackupConfigurationScheduleForm from "@/components/BackupConfigurationScheduleForm";
import BackupConfigurationAdvancedForm from "@/components/BackupConfigurationAdvancedForm";

interface BackupConfigurationCreateProps {
  render: boolean;
  setRender: React.Dispatch<React.SetStateAction<boolean>>;
}

function BackupConfigurationCreate({
  render,
  setRender,
}: BackupConfigurationCreateProps) {
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

  const [storageServerNames, setStorageServerNames] = useState<
    {
      id: number;
      name: string;
    }[]
  >([]);

  useEffect(() => {
    const fetchStorageServer = async () => {
      const res = await get("/storage-servers/names");
      if (res.status === 200) {
        const data = (await res) as {
          data: {
            id: number;
            name: string;
          }[];
        };

        setStorageServerNames(data.data);
      }
    };
    fetchStorageServer();
  }, []);

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
  const [scheduleCron, setScheduleCron] = useState("");
  const [retentionPolicy, setRetentionPolicy] = useState("{}");
  const [compression, setCompression] = useState('{"type": "tar"}');
  const [encryption, setEncryption] = useState('{"type": "none", "key": null}');
  const [integrityCheck, setIntegrityCheck] = useState(
    '{"type": "none", "hash": null}'
  );

  const [basicTabMissingValues, setBasicTabMissingValues] = useState(true);
  const [scheduleTabMissingValues, setScheduleTabMissingValues] =
    useState(true);
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
          <Alert severity="success"> Backup Configuration Created </Alert>
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
          endIcon={<AddIcon />}
          onClick={async () => {
            if (missingValues) {
              setOnError(true);
              setTimeout(() => {
                setOnError(false);
              }, 2000);
            } else {
              const res = await post("/backup-configurations/store", {
                name: name,
                data_source_ids: dataSources.map((source) => source.id),
                storage_server_ids: storageServers.map((server) => server.id),
                schedule_cron: scheduleCron,
                retention_policy_config: retentionPolicy,
                compression_config: compression,
                encryption_config: encryption,
                integrity_check_config: integrityCheck,
              });

              if (res.status === 201) {
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
          Create
        </Button>
      </div>
      <TabSection
        tabs={[
          {
            missingValues: basicTabMissingValues,
            label: "Basic",
            component: (
              <BackupConfigurationBasicForm
                dataSourceNames={dataSourceNames}
                dataSources={dataSources}
                setDataSources={setDataSources}
                storageServerNames={storageServerNames}
                storageServers={storageServers}
                setStorageServers={setStorageServers}
                name={name}
                setName={setName}
                setMissingValues={setBasicTabMissingValues}
              />
            ),
          },
          {
            missingValues: scheduleTabMissingValues,
            label: "Schedule",
            component: (
              <BackupConfigurationScheduleForm
                scheduleCron={scheduleCron}
                setScheduleCron={setScheduleCron}
                setMissingValues={setScheduleTabMissingValues}
              />
            ),
          },
          {
            missingValues: advancedTabMissingValues,
            label: "Advanced",
            component: (
              <BackupConfigurationAdvancedForm
                retentionPolicy={retentionPolicy}
                setRetentionPolicy={setRetentionPolicy}
                compression={compression}
                setCompression={setCompression}
                encryption={encryption}
                setEncryption={setEncryption}
                integrityCheck={integrityCheck}
                setIntegrityCheck={setIntegrityCheck}
                setMissingValues={setAdvancedTabMissingValues}
              />
            ),
          },
        ]}
      />
    </div>
  );
}

export default BackupConfigurationCreate;
